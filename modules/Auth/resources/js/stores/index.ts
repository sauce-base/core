import type { Pinia } from 'pinia';
import { defineStore } from 'pinia';
import { ref } from 'vue';

// Store
export const useAuthStore = defineStore('auth', () => {
    // State
    const counter = ref(0);

    // Actions
    const increment = () => {
        counter.value++;
    };

    const decrement = () => {
        counter.value--;
    };

    const reset = () => {
        counter.value = 0;
    };

    return {
        // State
        counter,
        // Actions
        increment,
        decrement,
        reset,
    };
});

// Store registration function for auto-discovery
export const registerStores = (pinia: Pinia, moduleName: string): void => {
    console.log(`✓ Registered ${moduleName} module stores`);
};
