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
                <!-- Background square -->
                <rect
                    x="15"
                    y="15"
                    width="70"
                    height="70"
                    rx="16"
                    fill="#6366F1"
                />

                <!-- Letter "T" -->
                <path
                    d="M35 35 L65 35 M50 35 L50 65"
                    stroke="white"
                    stroke-width="8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <!-- Completion dot -->
                <circle cx="65" cy="65" r="6" fill="#10B981" />
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
                tadone
            </h1>
            <p class="text-xs leading-tight text-gray-600 dark:text-gray-400">
                task done
            </p>
        </div>
    </div>
</template>
