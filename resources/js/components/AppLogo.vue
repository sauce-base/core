<script setup lang="ts">
defineProps<{
    size?: 'sm' | 'md' | 'lg' | 'xl' | 'xxl';
    showText?: boolean;
    centered?: boolean;
    variant?: 'brand' | 'light';
    showSubtitle?: boolean;
    subtitleSize?: 'xs' | 'sm' | 'md' | 'xl' | 'xxl';
}>();

const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-12 w-12',
    lg: 'h-16 w-16',
    xl: 'h-20 w-20',
    xxl: 'h-30 w-30',
};

const textSizeClasses = {
    sm: 'text-xl',
    md: 'text-2xl',
    lg: 'text-3xl',
    xl: 'text-4xl',
    xxl: 'text-6xl',
};

const subtitleSizeClasses = {
    xs: 'text-xs',
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl',
    xxl: 'text-2xl',
};

const defaultLogoSrc = '/images/logo.svg';
const lightLogoSrc = '/images/logo-white.svg';
const logoAlt = 'Saucebase logo';
</script>

<template>
    <div
        :class="
            centered
                ? 'flex flex-col items-center gap-1'
                : 'flex items-center gap-1'
        "
    >
        <!-- SVG Logo -->
        <div class="relative">
            <img
                :src="variant === 'light' ? lightLogoSrc : defaultLogoSrc"
                :alt="logoAlt"
                :class="['object-contain', sizeClasses[size || 'md']]"
                loading="lazy"
            />
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
                    variant === 'light'
                        ? 'leading-none font-bold text-white'
                        : 'leading-none font-bold text-gray-900 dark:text-white',
                ]"
            >
                <span class="text-secondary">sauce</span>
                <span class="text-primary">base</span>
            </h1>
            <p
                v-if="showSubtitle !== false"
                :class="[
                    subtitleSizeClasses[subtitleSize || size || 'sm'],
                    'leading-tight',
                    variant === 'light'
                        ? 'text-white/80'
                        : 'text-gray-600 dark:text-gray-400',
                ]"
            >
                the recipe that works
            </p>
        </div>
    </div>
</template>
