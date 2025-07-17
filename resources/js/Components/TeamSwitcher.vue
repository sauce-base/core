<script setup lang="ts">
import { ChevronsUpDown, Plus } from 'lucide-vue-next';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { type Component, ref } from 'vue';

import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/Components/ui/sidebar';

const props = defineProps<{
    teams: {
        name: string;
        logo: Component;
        plan: string;
    }[];
}>();

const { isMobile } = useSidebar();
const activeTeam = ref(props.teams[0]);
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    >
                        <div
                            class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg"
                        >
                            <component :is="activeTeam.logo" class="size-4" />
                        </div>
                        <div
                            class="grid flex-1 text-left text-sm leading-tight"
                        >
                            <span class="truncate font-medium">
                                {{ activeTeam.name }}
                            </span>
                            <span class="truncate text-xs">{{
                                activeTeam.plan
                            }}</span>
                        </div>
                        <ChevronsUpDown class="ml-auto" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                    align="start"
                    :side="isMobile ? 'bottom' : 'right'"
                    :side-offset="4"
                >
                    <DropdownMenuLabel class="text-muted-foreground text-xs">
                        Teams
                    </DropdownMenuLabel>
                    <DropdownMenuItem
                        v-for="(team, index) in teams"
                        :key="team.name"
                        class="gap-2 p-2"
                        @click="activeTeam = team"
                    >
                        <div
                            class="flex size-6 items-center justify-center rounded-sm border"
                        >
                            <component
                                :is="team.logo"
                                class="size-3.5 shrink-0"
                            />
                        </div>
                        {{ team.name }}
                        <DropdownMenuShortcut
                            >⌘{{ index + 1 }}</DropdownMenuShortcut
                        >
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem class="gap-2 p-2">
                        <div
                            class="flex size-6 items-center justify-center rounded-md border bg-transparent"
                        >
                            <Plus class="size-4" />
                        </div>
                        <div class="text-muted-foreground font-medium">
                            Add team
                        </div>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
