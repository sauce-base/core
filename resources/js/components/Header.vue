<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import ThemeSelector from '@/components/ThemeSelector.vue';
import { Link } from '@inertiajs/vue3';
import LanguageSelector from '@modules/Localization/resources/js/components/LanguageSelector.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import IconMenu from '~icons/heroicons/bars-3';
import IconX from '~icons/heroicons/x-mark';

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const activeSection = ref('');

const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;

    // Update active section based on scroll position
    const sections = ['features', 'pricing', 'faq'];
    const currentSection = sections.find((section) => {
        const element = document.getElementById(section);
        if (element) {
            const rect = element.getBoundingClientRect();
            return rect.top <= 100 && rect.bottom >= 100;
        }
        return false;
    });
    activeSection.value = currentSection || '';
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
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
                ? 'border-bß bg-white/50 shadow-sm backdrop-blur-md dark:border-gray-800/30 dark:bg-gray-950/50'
                : 'bg-transparent'
        "
    >
        <nav class="mx-auto max-w-7xl px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo - Enhanced prominence -->
                <Link
                    href="/"
                    class="flex items-center transition-opacity hover:opacity-80"
                >
                    <ApplicationLogo size="md" :showText="true" />
                </Link>

                <!-- Desktop Actions - Better hierarchy -->
                <div class="hidden items-center space-x-3 lg:flex">
                    <div class="flex items-center space-x-1">
                        <LanguageSelector mode="standalone" />
                        <ThemeSelector mode="standalone" />
                    </div>

                    <Link
                        v-if="canLogin && !$page.props.auth?.user"
                        :href="route('login')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    >
                        {{ $t('Sign In') }}
                    </Link>

                    <Link
                        v-if="canRegister && !$page.props.auth?.user"
                        :href="route('register')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:scale-105 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        {{ $t('Get Started') }}
                    </Link>

                    <Link
                        v-if="$page.props.auth?.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:scale-105 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        {{ $t('Dashboard') }}
                    </Link>
                </div>

                <!-- Mobile Menu Button - Better positioning -->
                <div class="flex items-center space-x-3 lg:hidden">
                    <LanguageSelector mode="standalone" />
                    <ThemeSelector mode="standalone" />
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="rounded-lg p-2 text-gray-700 transition-colors duration-200 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
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
                                :href="route('login')"
                                class="rounded-lg px-4 py-3 text-base font-medium text-gray-900 transition-colors duration-200 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $t('Sign In') }}
                            </Link>

                            <Link
                                v-if="canRegister && !$page.props.auth?.user"
                                :href="route('register')"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $t('Get Started') }}
                            </Link>

                            <Link
                                v-if="$page.props.auth?.user"
                                :href="route('dashboard')"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
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
