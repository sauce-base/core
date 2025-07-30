<script setup lang="ts">
defineProps<{
    size?: 'sm' | 'md' | 'lg' | 'xl';
    showText?: boolean;
    centered?: boolean;
}>();

const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-12 w-12',
    lg: 'h-16 w-16',
    xl: 'h-20 w-20',
};

const textSizeClasses = {
    sm: 'text-xl',
    md: 'text-2xl',
    lg: 'text-3xl',
    xl: 'text-4xl',
};
</script>

<template>
    <div
        :class="
            centered
                ? 'flex flex-col items-center gap-3'
                : 'flex items-center gap-3'
        "
    >
        <!-- SVG Logo -->
        <div class="relative">
            <svg
                :class="sizeClasses[size || 'md']"
                viewBox="0 0 100 100"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <!-- Background circle with gradient -->
                <defs>
                    <radialGradient
                        id="sauceGradient"
                        cx="0.3"
                        cy="0.3"
                        r="0.8"
                    >
                        <stop offset="0%" stop-color="#F97316" />
                        <stop offset="100%" stop-color="#DC2626" />
                    </radialGradient>
                </defs>

                <!-- Main sauce drop shape -->
                <path
                    d="M50 15 C60 15, 70 25, 70 35 C70 45, 65 50, 60 55 L55 65 C53 70, 52 75, 50 80 C48 75, 47 70, 45 65 L40 55 C35 50, 30 45, 30 35 C30 25, 40 15, 50 15 Z"
                    fill="url(#sauceGradient)"
                />

                <!-- Sauce highlight -->
                <ellipse
                    cx="45"
                    cy="30"
                    rx="8"
                    ry="12"
                    fill="#FED7AA"
                    opacity="0.6"
                />

                <!-- Subtle sauce droplet -->
                <circle cx="65" cy="70" r="3" fill="#DC2626" opacity="0.8" />
            </svg>
        </div>

        <!-- Text Logo -->
        <div
            v-if="showText"
            :class="
                centered
                    ? 'flex flex-col items-center text-center'
                    : 'flex flex-col'
            "
        >
            <h1
                :class="[
                    textSizeClasses[size || 'md'],
                    'leading-none font-bold text-gray-900 dark:text-white',
                ]"
            >
                sauce base
            </h1>
            <p class="text-xs leading-tight text-gray-600 dark:text-gray-400">
                your saas foundation
            </p>
        </div>
    </div>
</template>
