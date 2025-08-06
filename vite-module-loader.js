import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath, pathToFileURL } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function collectModuleAssetsPaths(paths, modulesPath) {
    const modulesFullPath = path.join(__dirname, modulesPath);
    const moduleStatusesPath = path.join(__dirname, 'modules_statuses.json');

    try {
        // Read module_statuses.json
        const moduleStatusesContent = await fs.readFile(
            moduleStatusesPath,
            'utf-8',
        );
        const moduleStatuses = JSON.parse(moduleStatusesContent);

        // Read module directories
        const moduleDirectories = await fs.readdir(modulesFullPath);

        for (const moduleDir of moduleDirectories) {
            // Check if the module is enabled (status is true)
            if (moduleStatuses[moduleDir] === true) {
                //Load paths from vite.config.js (for CSS/JS assets)
                const viteConfigPath = path.join(
                    modulesFullPath,
                    moduleDir,
                    'vite.config.js',
                );

                try {
                    await fs.access(viteConfigPath);
                    // Convert to a file URL for Windows compatibility
                    const moduleConfigURL = pathToFileURL(viteConfigPath);

                    // Import the module-specific Vite configuration
                    const moduleConfig = await import(moduleConfigURL.href);

                    if (
                        moduleConfig.paths &&
                        Array.isArray(moduleConfig.paths)
                    ) {
                        paths.push(...moduleConfig.paths);
                    }
                } catch (error) {
                    console.warn(
                        `Could not load vite config for module ${moduleDir}:`,
                        error.message,
                    );
                }
            }
        }
    } catch (error) {
        console.error(
            `Error reading module statuses or module configurations: ${error}`,
        );
    }

    return paths;
}

export default collectModuleAssetsPaths;
