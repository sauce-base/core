import type { App } from 'vue';

export interface ModuleSetup {
    setup?: (app?: App) => void | Promise<void>;
    afterMount?: () => void | Promise<void>;
}

/**
 * Discovers all module setup files from enabled modules
 * Uses Vite's import.meta.glob to eagerly load module app.ts files
 */
export function discoverModuleSetups() {
    return import.meta.glob<ModuleSetup>(
        '/modules/*/resources/js/app.ts',
        { eager: true }
    );
}

/**
 * Executes module setup functions before mounting
 * Collects afterMount callbacks for later execution
 * 
 * @param app - Vue app instance
 * @param moduleSetups - Module setup objects from discoverModuleSetups
 * @returns Array of afterMount callbacks to execute after mounting
 */
export async function executeModuleSetups(
    app: App,
    moduleSetups: Record<string, ModuleSetup>
): Promise<(() => void | Promise<void>)[]> {
    const afterMountCallbacks: (() => void | Promise<void>)[] = [];

    for (const module of Object.values(moduleSetups)) {
        if (module.setup) {
            await module.setup(app);
        }
        if (module.afterMount) {
            afterMountCallbacks.push(module.afterMount);
        }
    }

    return afterMountCallbacks;
}

/**
 * Executes all afterMount callbacks collected during setup
 * 
 * @param callbacks - Array of afterMount callbacks
 */
export async function executeAfterMountCallbacks(
    callbacks: (() => void | Promise<void>)[]
): Promise<void> {
    for (const callback of callbacks) {
        await callback();
    }
}
