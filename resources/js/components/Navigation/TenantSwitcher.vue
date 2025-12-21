<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ChevronsUpDown, Plus } from 'lucide-vue-next';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { computed, markRaw, ref } from 'vue';

import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';

interface Tenant {
    name: string;
    logo?: any;
    plan?: string;
}

const props = defineProps<{
    tenants?: Tenant[];
}>();

const { isMobile } = useSidebar();

// Use tenants from props if provided, otherwise use default
const tenants = computed(() => {
    return (
        props.tenants || [
            {
                name: 'Sauce Base',
                logo: markRaw(ApplicationLogo),
                plan: 'SaaS',
            },
        ]
    );
});

const activeTenant = ref(tenants.value[0]);
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <template v-if="tenants.length > 1">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <SidebarMenuButton
                            size="lg"
                            class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                        >
                            <div
                                class="text-sidebar-primary-foreground flex size-8 items-center justify-center rounded-lg p-0"
                            >
                                <component
                                    :is="activeTenant.logo"
                                    class="size-8"
                                />
                            </div>
                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-medium">
                                    {{ activeTenant.name }}
                                </span>
                                <span class="truncate text-xs">{{
                                    activeTenant.plan
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
                        <DropdownMenuLabel
                            class="text-muted-foreground text-xs"
                        >
                            Teams
                        </DropdownMenuLabel>
                        <DropdownMenuItem
                            v-for="(team, index) in tenants"
                            :key="team.name"
                            class="gap-2 p-2"
                            @click="activeTenant = team"
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
                            <!-- TODO: Implement keyboard shortcuts functionality -->
                            <DropdownMenuShortcut
                                >⇧⌘{{ index + 1 }}</DropdownMenuShortcut
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
                                Add tenant
                            </div>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </template>
            <template v-else>
                <SidebarMenuButton size="lg" as-child>
                    <Link href="/dashboard">
                        <div
                            class="text-sidebar-primary-foreground flex size-8 items-center justify-center rounded-lg p-0"
                        >
                            <component :is="activeTenant.logo" class="size-8" />
                        </div>
                        <div
                            class="grid flex-1 text-left text-sm leading-tight"
                        >
                            <span class="truncate font-medium">
                                {{ activeTenant.name }}
                            </span>
                            <span class="truncate text-xs opacity-40">{{
                                activeTenant.plan
                            }}</span>
                        </div>
                    </Link>
                </SidebarMenuButton>
            </template>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
