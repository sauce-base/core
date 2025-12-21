<script setup lang="ts">
import { useSidebar } from '@/components/ui/sidebar';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import IconGitHub from '~icons/mdi/github';

const { state, isMobile } = useSidebar();
</script>

<template>
    <Tooltip>
        <TooltipTrigger as-child>
            <a
                href="https://github.com/sauce-base/core"
                target="_blank"
                rel="noopener noreferrer"
                :class="[
                    'group flex items-center rounded-sm border border-white/10 bg-white/10 transition-colors hover:bg-white/20',
                    state === 'collapsed'
                        ? '-ml-3 size-10 justify-center'
                        : 'mx-0 gap-2 p-3 md:-mx-4',
                ]"
            >
                <IconGitHub
                    v-if="state === 'collapsed'"
                    class="text-foreground size-8"
                />
                <template v-else>
                    <div
                        class="flex size-8 shrink-0 items-center justify-center rounded-md bg-white/10"
                    >
                        <IconGitHub class="text-foreground size-8" />
                    </div>
                    <div class="flex-1">
                        <p
                            class="text-foreground text-xs leading-tight font-medium"
                        >
                            {{ $t('Star us on GitHub') }}
                        </p>
                        <p
                            class="text-muted-foreground text-[10px] leading-tight"
                        >
                            {{ $t('Help us grow the community') }}
                        </p>
                    </div>
                </template>
            </a>
        </TooltipTrigger>
        <TooltipContent
            side="right"
            align="center"
            :hidden="state !== 'collapsed' || isMobile"
        >
            {{ $t('Star us on GitHub') }}
        </TooltipContent>
    </Tooltip>
</template>
