import type { Pinia } from 'pinia';
import { createPinia } from 'pinia';
import { createPersistedState } from 'pinia-plugin-persistedstate';

const pinia = createPinia();

pinia.use(
    createPersistedState({
        key: (id) => `saucebase-${id}`,
        storage: localStorage,
    }),
);

// Interface for module store contracts
interface ModuleStoreContract {
    registerStores?: (pinia: Pinia, moduleName: string) => void | Promise<void>;
    storeManifest?: Record<string, () => Promise<any>>;
}

// Auto-discover and register module stores
async function discoverModuleStores() {
    try {
        // Dynamically import module stores using Vite's glob import
        const moduleStores = import.meta.glob(
            '../../modules/*/resources/js/stores/index.ts',
        );

        for (const [path, importStore] of Object.entries(moduleStores)) {
            const moduleName = path.match(/modules\/([^/]+)/)?.[1];

            if (!moduleName) continue;

            try {
                const module = (await importStore()) as ModuleStoreContract;

                // Validate and register stores
                if (typeof module.registerStores === 'function') {
                    await module.registerStores(pinia, moduleName);
                } else {
                    console.warn(
                        `Module ${moduleName}: registerStores function not found`,
                    );
                }

                // Validate store manifest
                if (
                    module.storeManifest &&
                    typeof module.storeManifest !== 'object'
                ) {
                    console.error(
                        `Module ${moduleName}: storeManifest must be an object`,
                    );
                }
            } catch (error) {
                console.error(
                    `Failed to register stores for module ${moduleName}:`,
                    error,
                );
            }
        }
    } catch (error) {
        console.error('Failed to discover module stores:', error);
    }
}

// Initialize module store discovery immediately
(async () => {
    await discoverModuleStores();
})();

export { discoverModuleStores, pinia };
