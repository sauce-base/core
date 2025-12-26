<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import LanguageSelector from '@/components/LanguageSelector.vue';
import ThemeSelector from '@/components/ThemeSelector.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import IconMenu from '~icons/heroicons/bars-3';
import IconX from '~icons/heroicons/x-mark';

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);

window.addEventListener('scroll', () => {
    isScrolled.value = window.scrollY > 10;
});

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <header
        class="fixed top-0 right-0 left-0 z-50 transition-all duration-300"
        :class="
            isScrolled
                ? 'border-b bg-white/5 shadow-sm backdrop-blur-md'
                : 'bg-transparent'
        "
    >
        <nav class="mx-auto max-w-7xl px-6 py-3">
            <div class="flex items-center justify-between">
                <Link
                    href="/"
                    class="flex items-center transition-opacity hover:opacity-80"
                >
                    <ApplicationLogo size="md" :showText="true" />
                </Link>

                <div class="hidden items-center space-x-3 lg:flex">
                    <div class="flex items-center space-x-1">
                        <LanguageSelector mode="standalone" />
                        <ThemeSelector mode="standalone" />
                    </div>

                    <Link
                        v-if="canLogin && !$page.props.auth?.user"
                        :href="route('auth.login')"
                        class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    >
                        {{ $t('Sign In') }}
                    </Link>

                    <Link
                        v-if="canRegister && !$page.props.auth?.user"
                        :href="route('auth.register')"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                    >
                        {{ $t('Get Started') }}
                    </Link>

                    <Link
                        v-if="
                            route().has('dashboard') && $page.props.auth?.user
                        "
                        :href="route('dashboard')"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                    >
                        {{ $t('Dashboard') }}
                    </Link>
                    <Link
                        v-if="
                            route().has('auth.logout') && $page.props.auth?.user
                        "
                        :href="route('auth.logout')"
                        class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white"
                    >
                        {{ $t('Logout') }}
                    </Link>
                </div>

                <!-- Mobile Menu Button - Better positioning -->
                <div class="flex items-center space-x-3 lg:hidden">
                    <LanguageSelector mode="standalone" />
                    <ThemeSelector mode="standalone" />
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="rounded-full p-2 text-gray-700 transition-colors duration-200 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        <IconMenu v-if="!mobileMenuOpen" class="h-6 w-6" />
                        <IconX v-else class="h-6 w-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile Menu - Enhanced with animations -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
            >
                <div
                    v-if="mobileMenuOpen"
                    class="mx-2 mt-4 rounded-lg border-t border-gray-200/40 bg-white/80 pb-6 backdrop-blur-sm lg:hidden dark:border-gray-800/40 dark:bg-gray-950/80"
                >
                    <div class="flex flex-col space-y-1 px-2 pt-4">
                        <div class="flex flex-col space-y-3">
                            <Link
                                v-if="canLogin && !$page.props.auth?.user"
                                :href="route('auth.login')"
                                class="hover:text-primary dark:hover:text-primary rounded-full px-4 py-3 text-base font-medium text-gray-900 transition-all duration-200 hover:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-800/50"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $t('Sign In') }}
                            </Link>

                            <Link
                                v-if="canRegister && !$page.props.auth?.user"
                                :href="route('auth.register')"
                                class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-4 py-3 text-base font-semibold transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $t('Get Started') }}
                            </Link>

                            <Link
                                v-if="
                                    route().has('dashboard') &&
                                    $page.props.auth?.user
                                "
                                :href="route('dashboard')"
                                class="bg-primary text-primary-foreground hover:bg-primary/90 focus:ring-primary inline-flex items-center justify-center rounded-full px-4 py-3 text-base font-semibold transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $t('Dashboard') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>
    </header>
</template>
