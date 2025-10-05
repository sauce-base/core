<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import PageTransition from '@/components/PageTransition.vue';
import ThemeSelector from '@/components/ThemeSelector.vue';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    title?: string;
    breadcrumbs?: Array<{
        label: string;
        url?: string;
    }>;
}>();
</script>

<template>
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator
                        orientation="vertical"
                        class="mr-2 data-[orientation=vertical]:h-4"
                    />
                    <Breadcrumb v-if="props.title || props.breadcrumbs">
                        <BreadcrumbList>
                            <template v-if="props.breadcrumbs">
                                <template
                                    v-for="(
                                        breadcrumb, index
                                    ) in props.breadcrumbs"
                                    :key="index"
                                >
                                    <BreadcrumbItem>
                                        <BreadcrumbLink
                                            v-if="breadcrumb.url"
                                            as-child
                                        >
                                            <Link :href="breadcrumb.url">
                                                {{ breadcrumb.label }}
                                            </Link>
                                        </BreadcrumbLink>
                                        <BreadcrumbPage v-else>
                                            {{ breadcrumb.label }}
                                        </BreadcrumbPage>
                                    </BreadcrumbItem>
                                    <BreadcrumbSeparator
                                        v-if="
                                            index < props.breadcrumbs.length - 1
                                        "
                                    />
                                </template>
                            </template>
                            <BreadcrumbItem v-else-if="props.title">
                                <BreadcrumbPage>{{
                                    props.title
                                }}</BreadcrumbPage>
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
                <div class="ml-auto pr-4">
                    <ThemeSelector mode="standalone" />
                </div>
            </header>

            <!-- Page Heading -->
            <header
                class="bg-white shadow-sm dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <PageTransition>
                    <slot />
                </PageTransition>
            </main>
        </SidebarInset>
    </SidebarProvider>
</template>
