<script setup lang="ts">
import Footer from '@/components/layout/Footer.vue';
import Header from '@/components/layout/Header.vue';
import Logo from '@/components/ui/Logo.vue';
import { Head, Link } from '@inertiajs/vue3';
import IconCheck from '~icons/heroicons/check';
import IconGitHub from '~icons/heroicons/code-bracket';
import IconGlobe from '~icons/heroicons/globe-alt';
import IconHeart from '~icons/heroicons/heart';
import IconLockOpen from '~icons/heroicons/lock-open';
import IconShieldCheck from '~icons/heroicons/shield-check';
import IconDashboard from '~icons/heroicons/squares-2x2';
import IconStar from '~icons/heroicons/star';
import IconUserPlus from '~icons/heroicons/user-plus';
import IconUsers from '~icons/heroicons/users';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="Sauce Base - Modern Laravel SaaS Starter Kit" />
    <div class="flex min-h-screen flex-col bg-white dark:bg-gray-950">
        <!-- Header with theme toggle -->
        <Header :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero Section -->
        <section
            class="flex flex-1 flex-col items-center justify-center px-6 py-24 pt-32"
        >
            <div class="mx-auto max-w-4xl text-center">
                <!-- Free Badge -->
                <div
                    class="mb-6 inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-800 dark:bg-green-900/20 dark:text-green-300"
                >
                    <IconStar class="mr-2 h-4 w-4" />
                    100% Free & Open Source
                </div>

                <!-- Logo -->
                <div class="mb-8 flex justify-center">
                    <Logo size="xl" :showText="true" />
                </div>

                <!-- Main Headline -->
                <h1
                    class="mb-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white"
                >
                    Modern Laravel SaaS
                    <span class="text-blue-600 dark:text-blue-400"
                        >Starter Kit</span
                    >
                </h1>

                <!-- Subheadline -->
                <p class="mb-8 text-xl text-gray-600 dark:text-gray-400">
                    Clone the repo, start building scalable and maintainable
                    SaaS applications quickly. Built with the VILT stack -
                    completely free and open source.
                </p>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <!-- Primary CTA -->
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconUserPlus class="mr-2 h-5 w-5" />
                        Get Started Free
                    </Link>

                    <!-- Dashboard Button (if logged in) -->
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                    >
                        <IconDashboard class="mr-2 h-5 w-5" />
                        Go to Dashboard
                    </Link>

                    <!-- GitHub Button -->
                    <a
                        href="https://github.com/roble/saucebase"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-8 py-4 text-lg font-semibold text-gray-700 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-950"
                    >
                        <IconGitHub class="mr-2 h-5 w-5" />
                        View on GitHub
                    </a>

                    <!-- Sign In Button -->
                    <Link
                        v-if="canLogin && !$page.props.auth.user"
                        :href="route('login')"
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        Already have an account? Sign In
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section
            id="features"
            class="bg-gray-50 px-6 py-24 dark:bg-gray-900/50"
        >
            <div class="mx-auto max-w-6xl">
                <div class="text-center">
                    <h2
                        class="mb-4 text-3xl font-bold text-gray-900 dark:text-white"
                    >
                        Everything You Need to Launch Your SaaS
                    </h2>
                    <p class="mb-16 text-lg text-gray-600 dark:text-gray-400">
                        Production-ready features and modern development tools
                        to accelerate your project
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20"
                        >
                            <IconCheck
                                class="h-6 w-6 text-blue-600 dark:text-blue-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Authentication & Authorization
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Multi-provider social login, role-based permissions,
                            and secure session management out of the box.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20"
                        >
                            <IconUsers
                                class="h-6 w-6 text-green-600 dark:text-green-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Modern Tech Stack
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Laravel 12, Vue 3, TypeScript, Inertia.js, and
                            Tailwind CSS - the perfect combination for modern
                            web apps.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20"
                        >
                            <IconShieldCheck
                                class="h-6 w-6 text-purple-600 dark:text-purple-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Production Ready
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Docker configuration, Redis caching, PostgreSQL
                            database, and optimized build pipeline included.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/20"
                        >
                            <IconLockOpen
                                class="h-6 w-6 text-orange-600 dark:text-orange-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Developer Experience
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Hot reload, TypeScript support, ESLint, PHP CS
                            Fixer, and comprehensive testing setup.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/20"
                        >
                            <IconHeart
                                class="h-6 w-6 text-red-600 dark:text-red-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            UI Components
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            shadcn/ui components with reka-ui implementation,
                            dark mode support, and responsive design.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/20"
                        >
                            <IconGlobe
                                class="h-6 w-6 text-teal-600 dark:text-teal-400"
                            />
                        </div>
                        <h3
                            class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Testing & Quality
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Comprehensive PHP (Pest) and E2E (Playwright)
                            testing with CI/CD pipeline configuration.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="px-6 py-24">
            <div class="mx-auto max-w-4xl text-center">
                <h2
                    class="mb-4 text-3xl font-bold text-gray-900 dark:text-white"
                >
                    Simple, Transparent Pricing
                </h2>
                <p class="mb-16 text-lg text-gray-600 dark:text-gray-400">
                    No hidden fees, no premium plans, no limitations - just code
                    and build
                </p>

                <div class="mx-auto max-w-md">
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                    >
                        <div class="mb-8">
                            <h3
                                class="mb-2 text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                SaaS Starter Kit
                            </h3>
                            <div class="mb-4">
                                <span
                                    class="text-6xl font-bold text-gray-900 dark:text-white"
                                    >$0</span
                                >
                                <span
                                    class="text-lg text-gray-600 dark:text-gray-400"
                                    >/forever</span
                                >
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                Everything included, no restrictions
                            </p>
                        </div>

                        <ul class="mb-8 space-y-3">
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Complete Laravel SaaS foundation</span
                                >
                            </li>
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Authentication & authorization</span
                                >
                            </li>
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Modern UI components</span
                                >
                            </li>
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Production deployment ready</span
                                >
                            </li>
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Comprehensive testing suite</span
                                >
                            </li>
                            <li class="flex items-center">
                                <IconCheck
                                    class="mr-3 h-5 w-5 text-green-500"
                                />
                                <span class="text-gray-700 dark:text-gray-300"
                                    >Active community support</span
                                >
                            </li>
                        </ul>

                        <Link
                            v-if="canRegister && !$page.props.auth.user"
                            :href="route('register')"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                        >
                            Get Started Free
                        </Link>

                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-hidden dark:focus:ring-offset-gray-950"
                        >
                            Go to Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ship Faster Section -->
        <section class="px-6 py-24">
            <div class="mx-auto max-w-6xl">
                <div class="text-center">
                    <h2
                        class="mb-4 text-3xl font-bold text-gray-900 dark:text-white"
                    >
                        Ship Faster
                    </h2>
                    <p class="mb-16 text-lg text-gray-600 dark:text-gray-400">
                        Get your SaaS up and running in minutes, not weeks
                    </p>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    <!-- Getting Started -->
                    <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-900/50">
                        <h3
                            class="mb-4 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Quick Start
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div
                                class="rounded bg-gray-900 p-4 font-mono text-green-400 dark:bg-gray-800"
                            >
                                <div class="mb-2"># Clone the repository</div>
                                <div class="text-gray-300">
                                    git clone
                                    https://github.com/roble/saucebase.git
                                </div>
                                <div class="mt-3 mb-2">
                                    # Install dependencies
                                </div>
                                <div class="text-gray-300">
                                    composer install && npm install
                                </div>
                                <div class="mt-3 mb-2"># Start development</div>
                                <div class="text-gray-300">composer dev</div>
                            </div>
                        </div>
                    </div>

                    <!-- Environment Setup -->
                    <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-900/50">
                        <h3
                            class="mb-4 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Environment Setup
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div
                                class="rounded bg-gray-900 p-4 font-mono text-green-400 dark:bg-gray-800"
                            >
                                <div class="mb-2"># Copy environment file</div>
                                <div class="text-gray-300">
                                    cp .env.example .env
                                </div>
                                <div class="mt-3 mb-2">
                                    # Generate application key
                                </div>
                                <div class="text-gray-300">
                                    php artisan key:generate
                                </div>
                                <div class="mt-3 mb-2"># Run migrations</div>
                                <div class="text-gray-300">
                                    php artisan migrate
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Docker Setup -->
                    <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-900/50">
                        <h3
                            class="mb-4 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Docker Setup (Alternative)
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div
                                class="rounded bg-gray-900 p-4 font-mono text-green-400 dark:bg-gray-800"
                            >
                                <div class="mb-2"># Start with Docker</div>
                                <div class="text-gray-300">
                                    docker-compose up -d
                                </div>
                                <div class="mt-3 mb-2">
                                    # Install dependencies
                                </div>
                                <div class="text-gray-300">
                                    docker-compose exec app composer install
                                </div>
                                <div class="mt-3 mb-2"># Run migrations</div>
                                <div class="text-gray-300">
                                    docker-compose exec app php artisan migrate
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Production Deployment -->
                    <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-900/50">
                        <h3
                            class="mb-4 text-xl font-semibold text-gray-900 dark:text-white"
                        >
                            Production Deployment
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div
                                class="rounded bg-gray-900 p-4 font-mono text-green-400 dark:bg-gray-800"
                            >
                                <div class="mb-2"># Build for production</div>
                                <div class="text-gray-300">npm run build</div>
                                <div class="mt-3 mb-2"># Optimize Laravel</div>
                                <div class="text-gray-300">
                                    php artisan optimize
                                </div>
                                <div class="mt-3 mb-2"># Run tests</div>
                                <div class="text-gray-300">composer test</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="bg-gray-50 px-6 py-24 dark:bg-gray-900/50">
            <div class="mx-auto max-w-4xl">
                <div class="text-center">
                    <h2
                        class="mb-4 text-3xl font-bold text-gray-900 dark:text-white"
                    >
                        Frequently Asked Questions
                    </h2>
                    <p class="mb-16 text-lg text-gray-600 dark:text-gray-400">
                        Everything you need to know about getting started
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            What technologies does Sauce Base use?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Sauce Base is built with Laravel 12, Vue 3,
                            TypeScript, Inertia.js, and Tailwind CSS. It also
                            includes PostgreSQL, Redis, and Docker for a
                            complete development environment.
                        </p>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Is Sauce Base really free?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Yes, Sauce Base is completely free and open source.
                            You can use it for personal projects, commercial
                            applications, or modify it as needed. No hidden
                            costs, no premium features locked away.
                        </p>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            What authentication methods are supported?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Sauce Base includes email/password authentication
                            plus social login with Google, GitHub, and Facebook.
                            All authentication is handled securely with
                            Laravel's built-in features.
                        </p>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            Can I deploy Sauce Base to production?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Absolutely! Sauce Base is production-ready with
                            Docker configuration, optimized build processes,
                            comprehensive testing, and performance
                            optimizations. Deploy to any cloud provider or your
                            own servers.
                        </p>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            What kind of support is available?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Community support is available through GitHub issues
                            and discussions. The codebase is well-documented and
                            follows Laravel and Vue.js best practices for easy
                            understanding.
                        </p>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div
                        class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800"
                    >
                        <h3
                            class="mb-3 text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            How do I customize the UI components?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Sauce Base uses shadcn/ui components with reka-ui
                            implementation. All components are fully
                            customizable and located in the
                            `resources/js/Components/ui/` directory. Modify
                            styling with Tailwind CSS classes.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-blue-600 px-6 py-24 dark:bg-blue-700">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="mb-4 text-3xl font-bold text-white">
                    Ready to Get Started?
                </h2>
                <p class="mb-8 text-xl text-blue-100">
                    Join developers who've already discovered the power of
                    modern Laravel SaaS development.
                </p>
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <Link
                        v-if="canRegister && !$page.props.auth.user"
                        :href="route('register')"
                        class="inline-flex items-center justify-center rounded-lg bg-white px-8 py-4 text-lg font-semibold text-blue-600 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 focus:outline-hidden"
                    >
                        <IconUserPlus class="mr-2 h-5 w-5" />
                        Start Free Today
                    </Link>

                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center justify-center rounded-lg bg-white px-8 py-4 text-lg font-semibold text-blue-600 transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 focus:outline-hidden"
                    >
                        <IconDashboard class="mr-2 h-5 w-5" />
                        Go to Dashboard
                    </Link>

                    <a
                        href="https://github.com/roble/saucebase"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-lg border-2 border-white px-8 py-4 text-lg font-semibold text-white transition-colors hover:bg-white hover:text-blue-600 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 focus:outline-hidden"
                    >
                        <IconGitHub class="mr-2 h-5 w-5" />
                        Star on GitHub
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
    </div>
</template>
