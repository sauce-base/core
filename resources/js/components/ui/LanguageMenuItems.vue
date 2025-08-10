<script setup lang="ts">
import {
    DropdownMenuItem,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
} from '@/components/ui/dropdown-menu';
import { useUIStore } from '@/stores/ui';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { Globe } from 'lucide-vue-next';

const uiStore = useUIStore();

const languages = [
    { code: 'en', name: 'English', flag: '🇺🇸' },
    { code: 'pt_BR', name: 'Português', flag: '🇧🇷' },
];

const switchLanguage = async (langCode: string) => {
    await loadLanguageAsync(langCode);
    uiStore.setLanguage(langCode);
};
</script>

<template>
    <DropdownMenuSub>
        <DropdownMenuSubTrigger
            class="[&>svg]:text-muted-foreground [&>svg]:mr-2"
        >
            <Globe class="size-4" />
            Language
        </DropdownMenuSubTrigger>
        <DropdownMenuSubContent>
            <DropdownMenuItem
                v-for="language in languages"
                :key="language.code"
                @click="switchLanguage(language.code)"
                :class="{ 'bg-accent': uiStore.language === language.code }"
            >
                <span class="mr-2">{{ language.flag }}</span>
                {{ language.name }}
            </DropdownMenuItem>
        </DropdownMenuSubContent>
    </DropdownMenuSub>
</template>
