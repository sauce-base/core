/**
 * Module Asset Loader
 *
 * Automatically discovers and collects asset paths from enabled Laravel modules.
 * Integrates with the main Vite configuration to include module assets in the build process.
 *
 * @fileoverview This loader scans enabled modules and imports their vite.config.js files
 * to collect asset paths. Only modules marked as enabled in modules_statuses.json are processed.
 */

import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath, pathToFileURL } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * Collects asset paths from enabled modules
 *
 * Scans the modules directory for enabled modules and imports their vite.config.js
 * files to collect asset paths that should be included in the main build.
 *
 * @param {string[]} paths - Initial array of asset paths to extend
 * @param {string} modulesPath - Relative path to modules directory (default: 'modules')
 * @returns {Promise<string[]>} Array of all asset paths including discovered module assets
 *
 * @example
 * const initialPaths = ['resources/js/app.ts'];
 * const allPaths = await collectModuleAssetsPaths(initialPaths, 'modules');
 * // Returns: ['resources/js/app.ts', 'modules/Auth/resources/css/app.css', ...]
 */
async function collectModuleAssetsPaths(paths, modulesPath) {
    const modulesFullPath = path.join(__dirname, modulesPath);
    const moduleStatusesPath = path.join(__dirname, 'modules_statuses.json');

    try {
        // Read and parse modules_statuses.json
        const moduleStatusesContent = await fs.readFile(
            moduleStatusesPath,
            'utf-8',
        );
        const moduleStatuses = JSON.parse(moduleStatusesContent);

        // Read module directories and filter out non-directories
        const allItems = await fs.readdir(modulesFullPath, {
            withFileTypes: true,
        });
        const moduleDirectories = allItems
            .filter((item) => item.isDirectory())
            .map((item) => item.name)
            .filter((name) => !name.startsWith('.')); // Skip hidden directories

        // Process each enabled module
        for (const moduleDir of moduleDirectories) {
            // Check if the module is enabled
            if (moduleStatuses[moduleDir] !== true) {
                continue;
            }

            const viteConfigPath = path.join(
                modulesFullPath,
                moduleDir,
                'vite.config.js',
            );

            try {
                // Check if vite.config.js exists and import it
                await fs.access(viteConfigPath);
                const moduleConfigURL = pathToFileURL(viteConfigPath);
                const moduleConfig = await import(moduleConfigURL.href);

                // Validate and extract paths
                if (moduleConfig.paths && Array.isArray(moduleConfig.paths)) {
                    // Auto-prefix paths with module directory structure
                    const fullPaths = moduleConfig.paths.map(
                        (assetPath) =>
                            `modules/${moduleDir}/resources/${assetPath}`,
                    );
                    paths.push(...fullPaths);
                } else if (moduleConfig.paths) {
                    console.warn(
                        `Module ${moduleDir}: 'paths' export must be an array`,
                    );
                }
            } catch (error) {
                if (error.code === 'ENOENT') {
                    console.warn(
                        `Module ${moduleDir} is enabled but missing vite.config.js`,
                    );
                } else {
                    console.warn(
                        `Module ${moduleDir}: Invalid vite.config.js - ${error.message}`,
                    );
                }
            }
        }
    } catch (error) {
        console.error(`Failed to load module assets: ${error.message}`);
    }

    return paths;
}

export default collectModuleAssetsPaths;
