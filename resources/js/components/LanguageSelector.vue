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
import { useLocalizationStore } from '@/stores/localization';
import { usePage } from '@inertiajs/vue3';
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

const store = useLocalizationStore();
const page = usePage();
const locales = computed(() => page.props.locales || {});

// Icon mapping for different locales
const iconMap: Record<string, any> = {
    en: IconUS,
    pt_BR: IconBR,
};

// Map backend locales to language objects with icons
const languages = computed(() => {
    const localesData = locales.value;
    return Object.entries(localesData).map(([code, name]) => ({
        code,
        name: name as string,
        icon: iconMap[code] || Globe, // Fallback to Globe icon if not found
    }));
});

const switchLanguage = async (langCode: string) => {
    await store.setLanguage(langCode);
    await loadLanguageAsync(langCode);
};

const currentLanguage = computed(() => {
    const langs = languages.value;

    if (!langs || langs.length === 0) {
        return { code: 'en', name: 'English', icon: iconMap.en };
    }

    return langs.find((lang) => lang.code === store.language) || langs[0];
});
</script>

<template>
    <!-- Standalone Mode (Landing Page) -->
    <DropdownMenu v-if="mode === 'standalone'">
        <DropdownMenuTrigger as-child>
            <button :class="props.triggerClass">
                <slot name="trigger" :current-language="currentLanguage">
                    <component :is="currentLanguage.icon" class="size-4.5" />
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
                        store.language === language.code,
                }"
            >
                <component :is="language.icon" class="size-4" />
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
                <Globe class="size-3.5" />
                {{ $t('Language') }}
            </slot>
        </DropdownMenuSubTrigger>
        <DropdownMenuSubContent>
            <DropdownMenuItem
                v-for="language in languages"
                :key="language.code"
                @click="switchLanguage(language.code)"
                :class="{ 'bg-accent': store.language === language.code }"
            >
                <component :is="language.icon" class="h-4 w-4" />
                {{ language.name }}
            </DropdownMenuItem>
        </DropdownMenuSubContent>
    </DropdownMenuSub>
</template>
