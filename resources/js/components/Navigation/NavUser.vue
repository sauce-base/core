<script setup lang="ts">
import { ChevronsUpDown } from 'lucide-vue-next';

import LanguageSelector from '@/components/LanguageSelector.vue';
import ThemeSelector from '@/components/ThemeSelector.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import type { User } from '@/types';
import type { MenuItem } from '@/types/navigation';
import { handleAction } from '@/utils/actionHandlers';
import { resolveIcon } from '@/utils/iconResolver';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    user: User;
    items: MenuItem[];
}>();

const { isMobile } = useSidebar();

const userInitials = computed(() => {
    return props.user.name
        .split(' ')
        .map((word) => word.charAt(0).toUpperCase())
        .slice(0, 2)
        .join('');
});

function handleClick(item: MenuItem, event: MouseEvent) {
    if (item.action) {
        handleAction(item.action, event);
    }
}
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        data-testid="user-menu-trigger"
                        size="lg"
                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                    >
                        <Avatar class="h-8 w-8 rounded-lg">
                            <AvatarImage :src="user.avatar" :alt="user.name" />
                            <AvatarFallback class="rounded-lg">
                                {{ userInitials }}
                            </AvatarFallback>
                        </Avatar>
                        <div
                            class="grid flex-1 text-left text-sm leading-tight"
                        >
                            <span class="truncate font-medium">
                                {{ user.name }}
                            </span>
                            <span class="truncate text-xs">
                                {{ user.email }}
                            </span>
                        </div>
                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                    :side="isMobile ? 'bottom' : 'right'"
                    align="end"
                    :side-offset="4"
                >
                    <DropdownMenuLabel class="p-0 font-normal">
                        <div
                            class="flex items-center gap-2 px-1 py-1.5 text-left text-sm"
                        >
                            <Avatar class="h-8 w-8 rounded-lg">
                                <AvatarImage
                                    :src="user.avatar"
                                    :alt="user.name"
                                />
                                <AvatarFallback class="rounded-lg">
                                    {{ userInitials }}
                                </AvatarFallback>
                            </Avatar>
                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-semibold">
                                    {{ user.name }}
                                </span>
                                <span class="truncate text-xs">
                                    {{ user.email }}
                                </span>
                            </div>
                        </div>
                    </DropdownMenuLabel>

                    <template v-if="route().has('settings.profile')">
                        <DropdownMenuSeparator />
                        <DropdownMenuItem
                            as-child
                            v-if="route().has('settings.profile')"
                        >
                            <Link :href="route('settings.profile')">
                                <component
                                    :is="resolveIcon('user-circle')"
                                    class="mr-2 size-4"
                                />
                                {{ $t('Profile') }}
                            </Link>
                        </DropdownMenuItem>
                    </template>

                    <DropdownMenuSeparator />

                    <!-- Fixed components (Language & Theme selectors) -->
                    <DropdownMenuGroup>
                        <LanguageSelector mode="submenu" />
                        <ThemeSelector mode="submenu" />
                    </DropdownMenuGroup>

                    <!-- Dynamic navigation items -->
                    <template v-if="items && items.length > 0">
                        <DropdownMenuSeparator />
                        <DropdownMenuGroup>
                            <DropdownMenuItem
                                v-for="item in items"
                                :key="item.id || item.label"
                                as-child
                                :class="{ 'cursor-pointer': item.action }"
                                @click="
                                    item.action
                                        ? handleClick(item, $event)
                                        : undefined
                                "
                            >
                                <Link
                                    v-if="!item.action"
                                    :href="item.url || '#'"
                                >
                                    <component
                                        :is="resolveIcon(item.icon)"
                                        v-if="item.icon"
                                    />
                                    <span>{{ $t(item.label) }}</span>
                                </Link>
                                <div v-else>
                                    <component
                                        :is="resolveIcon(item.icon)"
                                        v-if="item.icon"
                                    />
                                    <span>{{ $t(item.label) }}</span>
                                </div>
                            </DropdownMenuItem>
                        </DropdownMenuGroup>
                    </template>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
