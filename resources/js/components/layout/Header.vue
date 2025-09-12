<script setup lang="ts">
import Logo from '@/components/ui/Logo.vue';
import ThemeSelector from '@/components/ui/ThemeSelector.vue';
import { Link } from '@inertiajs/vue3';
import LanguageSelector from '@modules/Localization/resources/js/components/LanguageSelector.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import IconExternalLink from '~icons/heroicons/arrow-top-right-on-square';
import IconMenu from '~icons/heroicons/bars-3';
import IconGitHub from '~icons/heroicons/code-bracket';
import IconCube from '~icons/heroicons/cube-transparent';
import IconCurrencyDollar from '~icons/heroicons/currency-dollar';
import IconDocument from '~icons/heroicons/document-text';
import IconQuestionMarkCircle from '~icons/heroicons/question-mark-circle';
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

const smoothScrollTo = (elementId: string) => {
    const element = document.getElementById(elementId);
    if (element) {
        const offsetTop = element.offsetTop - 80; // Account for fixed header
        window.scrollTo({
            top: offsetTop,
            behavior: 'smooth',
        });
    }
    mobileMenuOpen.value = false;
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
                ? 'border-b border-gray-200/30 bg-white/50 shadow-sm backdrop-blur-md dark:border-gray-800/30 dark:bg-gray-950/50'
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
                    <Logo size="md" :showText="true" />
                </Link>

                <!-- Desktop Navigation - Better spacing and active states -->
                <div class="hidden items-center space-x-8 lg:flex">
                    <button
                        @click="smoothScrollTo('features')"
                        class="relative flex items-center gap-2 px-2 py-1 text-sm font-medium transition-colors duration-200"
                        :class="
                            activeSection === 'features'
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white'
                        "
                    >
                        <IconCube class="h-4 w-4" />
                        {{ $t('Features') }}
                        <div
                            v-if="activeSection === 'features'"
                            class="absolute right-0 -bottom-1 left-0 h-0.5 rounded-full bg-blue-600 dark:bg-blue-400"
                        />
                    </button>
                    <button
                        @click="smoothScrollTo('pricing')"
                        class="relative flex items-center gap-2 px-2 py-1 text-sm font-medium transition-colors duration-200"
                        :class="
                            activeSection === 'pricing'
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white'
                        "
                    >
                        <IconCurrencyDollar class="h-4 w-4" />
                        {{ $t('Pricing') }}
                        <div
                            v-if="activeSection === 'pricing'"
                            class="absolute right-0 -bottom-1 left-0 h-0.5 rounded-full bg-blue-600 dark:bg-blue-400"
                        />
                    </button>
                    <button
                        @click="smoothScrollTo('faq')"
                        class="relative flex items-center gap-2 px-2 py-1 text-sm font-medium transition-colors duration-200"
                        :class="
                            activeSection === 'faq'
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white'
                        "
                    >
                        <IconQuestionMarkCircle class="h-4 w-4" />
                        {{ $t('FAQ') }}
                        <div
                            v-if="activeSection === 'faq'"
                            class="absolute right-0 -bottom-1 left-0 h-0.5 rounded-full bg-blue-600 dark:bg-blue-400"
                        />
                    </button>
                    <a
                        href="#"
                        class="flex items-center gap-2 px-2 py-1 text-sm font-medium text-gray-700 transition-colors duration-200 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    >
                        <IconDocument class="h-4 w-4" />
                        {{ $t('Docs') }}
                    </a>
                    <a
                        href="https://github.com/sauce-base/core"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 px-2 py-1 text-sm font-medium text-gray-700 transition-colors duration-200 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    >
                        <IconGitHub class="h-4 w-4" />
                        {{ $t('GitHub') }}
                        <IconExternalLink class="h-3 w-3 opacity-60" />
                    </a>
                </div>

                <!-- Desktop Actions - Better hierarchy -->
                <div class="hidden items-center space-x-3 lg:flex">
                    <div class="flex items-center space-x-1">
                        <LanguageSelector mode="standalone" />
                        <ThemeSelector mode="standalone" />
                    </div>

                    <div class="h-6 w-px bg-gray-300 dark:bg-gray-600" />

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
                        <button
                            @click="smoothScrollTo('features')"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-left text-base font-medium transition-colors duration-200"
                            :class="
                                activeSection === 'features'
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'text-gray-900 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400'
                            "
                        >
                            <IconCube class="h-5 w-5" />
                            {{ $t('Features') }}
                        </button>
                        <button
                            @click="smoothScrollTo('pricing')"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-left text-base font-medium transition-colors duration-200"
                            :class="
                                activeSection === 'pricing'
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'text-gray-900 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400'
                            "
                        >
                            <IconCurrencyDollar class="h-5 w-5" />
                            {{ $t('Pricing') }}
                        </button>
                        <button
                            @click="smoothScrollTo('faq')"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-left text-base font-medium transition-colors duration-200"
                            :class="
                                activeSection === 'faq'
                                    ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                    : 'text-gray-900 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400'
                            "
                        >
                            <IconQuestionMarkCircle class="h-5 w-5" />
                            {{ $t('FAQ') }}
                        </button>
                        <a
                            href="#"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-left text-base font-medium text-gray-900 transition-colors duration-200 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400"
                            @click="mobileMenuOpen = false"
                        >
                            <IconDocument class="h-5 w-5" />
                            {{ $t('Docs') }}
                        </a>
                        <a
                            href="https://github.com/sauce-base/core"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-left text-base font-medium text-gray-900 transition-colors duration-200 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-100 dark:hover:bg-gray-800/50 dark:hover:text-blue-400"
                            @click="mobileMenuOpen = false"
                        >
                            <IconGitHub class="h-5 w-5" />
                            <span class="flex items-center gap-2">
                                {{ $t('GitHub') }}
                                <IconExternalLink class="h-4 w-4 opacity-60" />
                            </span>
                        </a>

                        <div
                            class="mt-4 border-t border-gray-200/50 pt-4 dark:border-gray-800/50"
                        >
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
                                    v-if="
                                        canRegister && !$page.props.auth?.user
                                    "
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
                </div>
            </Transition>
        </nav>
    </header>
</template>
