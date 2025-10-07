<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useColorMode } from '@vueuse/core';
import { computed } from 'vue';
import IconAuto from '~icons/fluent/dark-theme-20-filled';
import IconMoon from '~icons/heroicons/moon';
import IconSun from '~icons/heroicons/sun';

interface Props {
    /**
     * Display mode - 'standalone' for main menu, 'submenu' for nested dropdown
     */
    mode?: 'standalone' | 'submenu';
    /**
     * Custom trigger class for standalone mode
     */
    triggerClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
    mode: 'standalone',
    triggerClass:
        'flex items-center rounded-lg p-2 text-gray-700 transition-colors duration-200 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
});

const colorMode = useColorMode({
    emitAuto: true,
});

const themes = [
    { code: 'light', name: 'Light', icon: IconSun },
    { code: 'dark', name: 'Dark', icon: IconMoon },
    { code: 'auto', name: 'Device', icon: IconAuto },
] as const;

const switchTheme = (themeCode: string) => {
    colorMode.value = themeCode as 'light' | 'dark' | 'auto';
};

const currentTheme = computed(
    () => themes.find((theme) => theme.code === colorMode.value) || themes[0],
);
</script>

<template>
    <!-- Standalone Mode (Landing Page) -->
    <DropdownMenu v-if="mode === 'standalone'">
        <DropdownMenuTrigger as-child>
            <button :class="props.triggerClass">
                <slot name="trigger" :current-theme="currentTheme">
                    <component :is="currentTheme.icon" class="h-5 w-5" />
                </slot>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="min-w-[160px]">
            <DropdownMenuItem
                v-for="theme in themes"
                :key="theme.code"
                @click="switchTheme(theme.code)"
                :class="{
                    'bg-accent text-accent-foreground':
                        colorMode === theme.code,
                }"
            >
                <component :is="theme.icon" class="h-4 w-4" />
                {{ $t(theme.name) }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Submenu Mode (NavUser) -->
    <DropdownMenuSub v-else>
        <DropdownMenuSubTrigger
            class="[&>svg]:text-muted-foreground [&>svg]:mr-2"
        >
            <slot name="submenu-trigger" :current-theme="currentTheme">
                <component :is="currentTheme.icon" class="size-4" />
                {{ $t('Theme') }}
            </slot>
        </DropdownMenuSubTrigger>
        <DropdownMenuSubContent>
            <DropdownMenuItem
                v-for="theme in themes"
                :key="theme.code"
                @click="switchTheme(theme.code)"
                :class="{ 'bg-accent': colorMode === theme.code }"
            >
                <component :is="theme.icon" class="h-4 w-4" />
                {{ $t(theme.name) }}
            </DropdownMenuItem>
        </DropdownMenuSubContent>
    </DropdownMenuSub>
</template>
