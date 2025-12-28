import {
    existsSync,
    mkdirSync,
    readdirSync,
    statSync,
    writeFileSync,
} from 'node:fs';
import { readFile } from 'node:fs/promises';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { loadEnabledModuleNames } from '../../../module-loader.js';
import { generateIconRegistryTemplate } from './template.js';

const __dirname = dirname(fileURLToPath(import.meta.url));

/**
 * Vite plugin for automatic icon registry generation
 *
 * Scans PHP files for icon patterns and generates a TypeScript registry
 * with imports and mappings for use in Vue components.
 */
export function iconRegistryGenerator(options = {}) {
    const {
        scanPaths = ['app', 'modules/*/app'],
        outputPath = 'resources/js/generated/icon-registry.ts',
        debounceMs = 300,
    } = options;

    const outputFullPath = resolve(__dirname, '../../..', outputPath);
    let debounceTimer = null;

    /**
     * Find PHP files that contain icon references
     * Scans Providers, Controllers, and config files
     */
    async function findIconFiles() {
        const projectRoot = resolve(__dirname, '../../..');
        const enabledModules = await loadEnabledModuleNames(projectRoot);
        const files = [];

        for (const scanPath of scanPaths) {
            if (scanPath.includes('modules/*/')) {
                // Handle module patterns
                const modulesDir = resolve(projectRoot, 'modules');
                if (!existsSync(modulesDir)) {
                    continue;
                }

                // Extract the path after 'modules/*/'
                const pathAfterModules = scanPath.replace('modules/*/', '');
                const modulesToScan =
                    enabledModules.length > 0
                        ? enabledModules
                        : readdirSync(modulesDir);

                for (const module of modulesToScan) {
                    const modulePath = join(
                        modulesDir,
                        module,
                        pathAfterModules,
                    );
                    if (
                        existsSync(modulePath) &&
                        statSync(modulePath).isDirectory()
                    ) {
                        const entries = readdirSync(modulePath);
                        for (const entry of entries) {
                            if (entry.endsWith('.php')) {
                                files.push(join(modulePath, entry));
                            }
                        }
                    }
                }
            } else {
                // Scan core app paths
                const fullPath = resolve(projectRoot, scanPath);
                if (existsSync(fullPath) && statSync(fullPath).isDirectory()) {
                    const entries = readdirSync(fullPath);
                    for (const entry of entries) {
                        if (entry.endsWith('.php')) {
                            files.push(join(fullPath, entry));
                        }
                    }
                }
            }
        }

        return [...new Set(files)]; // Remove duplicates
    }

    /**
     * Extract icon identifiers from PHP file content
     * Pattern: 'icon' => 'library:icon-name' or "icon" => "library:icon-name"
     */
    function extractIcons(phpContent) {
        // Use bounded quantifiers to prevent ReDoS vulnerability
        // Limit library and icon names to 50 chars max ([\w-]{1,50})
        const iconPattern =
            /['"]icon['"]\s{0,100}=>\s{0,100}['"]([\w-]{1,50}):([\w-]{1,50})['"]/g;
        const icons = new Set();
        let match;

        while ((match = iconPattern.exec(phpContent)) !== null) {
            const library = match[1];
            const iconName = match[2];
            const iconIdentifier = `${library}:${iconName}`;
            icons.add(iconIdentifier);
        }

        return icons;
    }

    /**
     * Parse icon identifier into components
     */
    function parseIconIdentifier(iconIdentifier) {
        const [library, name] = iconIdentifier.split(':');

        if (!library || !name) {
            return null;
        }

        // SECURITY: Validate library and name to prevent code injection and path traversal
        // Only allow alphanumeric characters and hyphens
        const validPattern = /^[a-z0-9-]+$/i;
        const maxLength = 50;

        // Validate library
        if (!validPattern.test(library) || library.length > maxLength) {
            if (process.env.NODE_ENV !== 'production') {
                console.warn(
                    `[icon-registry] Rejected invalid library name: "${library}" (must match /^[a-z0-9-]+$/i and be <= ${maxLength} chars)`,
                );
            }
            return null;
        }

        // Validate icon name
        if (!validPattern.test(name) || name.length > maxLength) {
            if (process.env.NODE_ENV !== 'production') {
                console.warn(
                    `[icon-registry] Rejected invalid icon name: "${name}" (must match /^[a-z0-9-]+$/i and be <= ${maxLength} chars)`,
                );
            }
            return null;
        }

        // Convert kebab-case to PascalCase for import name
        const pascalName = name
            .split('-')
            .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
            .join('');

        return {
            identifier: iconIdentifier,
            library,
            name,
            importPath: `~icons/${library}/${name}`,
            importName: `Icon${pascalName}`,
        };
    }

    /**
     * Generate TypeScript code for icon registry
     */
    function generateTypeScriptCode(icons) {
        const parsedIcons = Array.from(icons)
            .map(parseIconIdentifier)
            .filter(Boolean)
            .sort((a, b) => {
                // Sort by library first, then by name
                if (a.library !== b.library) {
                    return a.library.localeCompare(b.library);
                }
                return a.name.localeCompare(b.name);
            });

        if (parsedIcons.length === 0) {
            console.warn('[icon-registry] No valid icons found');
            return null;
        }

        return generateIconRegistryTemplate(parsedIcons);
    }

    /**
     * Write icon registry to output file
     */
    function writeIconRegistry(code) {
        try {
            // Ensure directory exists
            const dir = dirname(outputFullPath);
            if (!existsSync(dir)) {
                mkdirSync(dir, { recursive: true });
            }

            // Write file
            writeFileSync(outputFullPath, code, 'utf-8');
            return true;
        } catch (error) {
            console.error(
                '[icon-registry] Failed to write registry:',
                error.message,
            );
            return false;
        }
    }

    /**
     * Generate icon registry from PHP files
     */
    async function generateIconRegistry() {
        try {
            const files = await findIconFiles();
            const allIcons = new Set();
            // SECURITY: Limit file size to prevent DoS via extremely large files (10MB max)
            const maxFileSizeBytes = 10 * 1024 * 1024;

            // Read and extract icons from each file
            await Promise.all(
                files.map(async (file) => {
                    try {
                        // Check file size before reading
                        const stats = statSync(file);
                        if (stats.size > maxFileSizeBytes) {
                            console.warn(
                                `[icon-registry] Skipping ${file}: file too large (${stats.size} bytes, max ${maxFileSizeBytes})`,
                            );
                            return;
                        }

                        const content = await readFile(file, 'utf-8');
                        const icons = extractIcons(content);
                        icons.forEach((icon) => allIcons.add(icon));
                    } catch (error) {
                        console.warn(
                            `[icon-registry] Could not read ${file}:`,
                            error.message,
                        );
                    }
                }),
            );

            // Generate TypeScript code
            const code = generateTypeScriptCode(allIcons);

            if (!code) {
                console.warn('[icon-registry] No icons to generate');
                return false;
            }

            // Write registry file
            const success = writeIconRegistry(code);

            if (success) {
                console.log(
                    `[icon-registry] ✓ Generated ${outputPath} (${allIcons.size} icons)`,
                );
                return true;
            }

            return false;
        } catch (error) {
            console.error('[icon-registry] Generation failed:', error.message);
            return false;
        }
    }

    /**
     * Debounced regeneration function
     */
    function scheduleRegeneration() {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        debounceTimer = setTimeout(async () => {
            await generateIconRegistry();
        }, debounceMs);
    }

    // Vite plugin implementation
    return {
        name: 'icon-registry-generator',

        async buildStart() {
            // Generate registry on build start
            await generateIconRegistry();
        },

        configureServer(server) {
            // Watch PHP files in dev mode
            const projectRoot = resolve(__dirname, '../../..');

            // Create glob patterns for specific PHP files only
            const watchGlobs = [];
            for (const scanPath of scanPaths) {
                if (scanPath.includes('modules/*/')) {
                    // For module patterns, create glob pattern
                    watchGlobs.push(resolve(projectRoot, scanPath, '*.php'));
                } else {
                    // For regular paths, watch PHP files
                    watchGlobs.push(resolve(projectRoot, scanPath, '*.php'));
                }
            }

            // Also watch modules_statuses.json for module enable/disable changes
            watchGlobs.push(resolve(projectRoot, 'modules_statuses.json'));

            // Use Vite's file watcher with glob patterns
            server.watcher.add(watchGlobs);

            server.watcher.on('change', (filePath) => {
                if (filePath.endsWith('modules_statuses.json')) {
                    console.log(
                        '[icon-registry] Module status changed, rescanning...',
                    );
                    scheduleRegeneration();
                } else if (filePath.endsWith('.php')) {
                    console.log('[icon-registry] Detected change in', filePath);
                    scheduleRegeneration();
                }
            });

            server.watcher.on('add', (filePath) => {
                if (filePath.endsWith('.php')) {
                    console.log('[icon-registry] Detected new file', filePath);
                    scheduleRegeneration();
                }
            });
        },
    };
}
