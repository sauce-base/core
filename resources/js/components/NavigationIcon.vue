<script setup lang="ts">
import { computed, type Component } from 'vue';
// Navigation icons from backend providers
// Add new icons here when adding new navigation items
import IconLogOut from '~icons/lucide/log-out';
import IconSettings from '~icons/lucide/settings';
import IconSettings2 from '~icons/lucide/settings-2';
import IconSquareTerminal from '~icons/lucide/square-terminal';
import IconUserCircle from '~icons/lucide/user-circle';

const props = defineProps<{ icon?: string | null }>();

/**
 * NavigationIcon - Resolves icons from backend navigation data
 *
 * This component maps backend icon strings (e.g., 'lucide:settings')
 * to actual icon components. It uses static imports because unplugin-icons
 * generates virtual modules at build time that cannot be dynamically imported.
 *
 * To add a new navigation icon:
 * 1. Import it: import IconFoo from '~icons/lucide/foo'
 * 2. Add to iconMap: 'lucide:foo': IconFoo
 */
const iconMap: Record<string, Component> = {
    'lucide:log-out': IconLogOut,
    'lucide:settings': IconSettings,
    'lucide:settings-2': IconSettings2,
    'lucide:square-terminal': IconSquareTerminal,
    'lucide:user-circle': IconUserCircle,
};

const iconComponent = computed(() => {
    if (!props.icon) return null;

    const icon = iconMap[props.icon];

    if (!icon && import.meta.env.DEV) {
        console.warn(
            `Icon not found: ${props.icon}. Add it to NavigationIcon.vue iconMap.`,
        );
    }

    return icon || null;
});
</script>

<template>
    <component :is="iconComponent" v-if="iconComponent" />
</template>
