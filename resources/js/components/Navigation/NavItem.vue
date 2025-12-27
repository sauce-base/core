<script setup lang="ts">
import NavigationIcon from '@/components/NavigationIcon.vue';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { Separator } from '@/components/ui/separator';
import {
    SidebarGroupLabel,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import type { MenuItem } from '@/types/navigation';
import { handleAction } from '@/utils/actionHandlers';
import { Link } from '@inertiajs/vue3';
import { computed, inject } from 'vue';
import IconChevronRight from '~icons/lucide/chevron-right';

const props = defineProps<{
    item: MenuItem;
}>();

// Inject tooltip visibility from parent sidebar (defaults to true)
const showTooltip = inject<boolean>('showTooltip', true);

// Type detection
const isSeparator = computed(() => props.item.type === 'separator');
const isLabel = computed(() => props.item.type === 'label');
const isAction = computed(() => !!props.item.action);
const hasChildren = computed(() => !!props.item.children?.length);

// Active state - prefer server-side from Spatie, fallback to Ziggy
const isActive = computed(() => {
    // Use server-side active state if available
    if (props.item.active !== undefined) {
        return props.item.active;
    }

    // Fallback to client-side Ziggy route matching
    if (!props.item.route) return false;
    try {
        return route().current(props.item.route);
    } catch {
        return false;
    }
});

// Action handler
function handleClick(event: MouseEvent) {
    if (props.item.action) {
        handleAction(props.item.action, event);
    }
}
</script>

<template>
    <!-- Separator -->
    <Separator v-if="isSeparator" />

    <!-- Label -->
    <SidebarGroupLabel v-else-if="isLabel">
        {{ $t(item.label) }}
    </SidebarGroupLabel>

    <!-- Collapsible group with children -->
    <Collapsible
        v-else-if="hasChildren"
        as-child
        :default-open="isActive"
        class="group/collapsible"
    >
        <SidebarMenuItem>
            <CollapsibleTrigger as-child>
                <SidebarMenuButton
                    :tooltip="showTooltip ? $t(item.label) : undefined"
                >
                    <NavigationIcon :icon="item.icon" />
                    <span>{{ $t(item.label) }}</span>
                    <IconChevronRight
                        class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                    />
                </SidebarMenuButton>
            </CollapsibleTrigger>
            <CollapsibleContent>
                <SidebarMenuSub>
                    <SidebarMenuSubItem
                        v-for="child in item.children"
                        :key="child.id || child.label"
                    >
                        <SidebarMenuSubButton
                            as-child
                            :is-active="
                                child.active !== undefined
                                    ? child.active
                                    : !!(
                                          child.route &&
                                          route().current(child.route)
                                      )
                            "
                        >
                            <Link :href="child.url || '#'">
                                <NavigationIcon :icon="child.icon" />
                                <span>{{ $t(child.label) }}</span>
                            </Link>
                        </SidebarMenuSubButton>
                    </SidebarMenuSubItem>
                </SidebarMenuSub>
            </CollapsibleContent>
        </SidebarMenuItem>
    </Collapsible>

    <!-- Action button -->
    <SidebarMenuItem v-else-if="isAction">
        <SidebarMenuButton
            :tooltip="showTooltip ? $t(item.label) : undefined"
            @click="handleClick"
        >
            <NavigationIcon :icon="item.icon" />
            <span>{{ $t(item.label) }}</span>
        </SidebarMenuButton>
    </SidebarMenuItem>

    <!-- Link -->
    <SidebarMenuItem v-else>
        <SidebarMenuButton
            as-child
            :is-active="isActive"
            :tooltip="showTooltip ? $t(item.label) : undefined"
        >
            <Link :href="item.url || '#'">
                <NavigationIcon :icon="item.icon" />
                <span>{{ $t(item.label) }}</span>
            </Link>
        </SidebarMenuButton>
    </SidebarMenuItem>
</template>
