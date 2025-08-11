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
import { useUIStore } from '@/stores/ui';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { Globe } from 'lucide-vue-next';
import { computed } from 'vue';
import IconBR from '~icons/circle-flags/br';
import IconUS from '~icons/circle-flags/us';

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

const uiStore = useUIStore();

const languages = [
    { code: 'en', name: 'English', icon: IconUS },
    { code: 'pt_BR', name: 'Português', icon: IconBR },
];

const switchLanguage = async (langCode: string) => {
    await loadLanguageAsync(langCode);
    uiStore.setLanguage(langCode);
};

const currentLanguage = computed(
    () =>
        languages.find((lang) => lang.code === uiStore.language) ||
        languages[0],
);
</script>

<template>
    <!-- Standalone Mode (Landing Page) -->
    <DropdownMenu v-if="mode === 'standalone'">
        <DropdownMenuTrigger as-child>
            <button :class="props.triggerClass">
                <slot name="trigger" :current-language="currentLanguage">
                    <component :is="currentLanguage.icon" class="h-5 w-5" />
                </slot>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="min-w-[160px]">
            <DropdownMenuItem
                v-for="language in languages"
                :key="language.code"
                @click="switchLanguage(language.code)"
                :class="{
                    'bg-accent text-accent-foreground':
                        uiStore.language === language.code,
                }"
            >
                <component :is="language.icon" class="h-4 w-4" />
                {{ language.name }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <!-- Submenu Mode (NavUser) -->
    <DropdownMenuSub v-else>
        <DropdownMenuSubTrigger
            class="[&>svg]:text-muted-foreground [&>svg]:mr-2"
        >
            <slot name="submenu-trigger" :current-language="currentLanguage">
                <Globe class="size-4" />
                {{ $t('Language') }}
            </slot>
        </DropdownMenuSubTrigger>
        <DropdownMenuSubContent>
            <DropdownMenuItem
                v-for="language in languages"
                :key="language.code"
                @click="switchLanguage(language.code)"
                :class="{ 'bg-accent': uiStore.language === language.code }"
            >
                <component :is="language.icon" class="h-4 w-4" />
                {{ language.name }}
            </DropdownMenuItem>
        </DropdownMenuSubContent>
    </DropdownMenuSub>
</template>
