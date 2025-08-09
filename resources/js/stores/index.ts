import { createPinia } from 'pinia';
import { createPersistedState } from 'pinia-plugin-persistedstate';

const pinia = createPinia();

pinia.use(
    createPersistedState({
        key: (id) => `${import.meta.env.VITE_LOCAL_STORAGE_KEY}-${id}`,
        storage: localStorage,
    }),
);

export { pinia };
