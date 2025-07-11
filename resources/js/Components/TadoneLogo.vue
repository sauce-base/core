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
                    <linearGradient
                        id="tadone-gradient"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="100%"
                    >
                        <stop offset="0%" stop-color="#3B82F6" />
                        <stop offset="100%" stop-color="#1D4ED8" />
                    </linearGradient>
                </defs>

                <!-- Main circle -->
                <circle
                    cx="50"
                    cy="50"
                    r="45"
                    fill="url(#tadone-gradient)"
                    class="drop-shadow-lg"
                />

                <!-- Inner circle for depth -->
                <circle
                    cx="50"
                    cy="50"
                    r="38"
                    fill="none"
                    stroke="rgba(255,255,255,0.2)"
                    stroke-width="1"
                />

                <!-- Checkmark -->
                <path
                    d="M30 48 L42 60 L70 32"
                    stroke="white"
                    stroke-width="6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    fill="none"
                />

                <!-- Task lines representing productivity -->
                <g
                    stroke="white"
                    stroke-width="2"
                    stroke-linecap="round"
                    opacity="0.7"
                >
                    <line x1="25" y1="25" x2="40" y2="25" />
                    <line x1="25" y1="30" x2="35" y2="30" />
                    <line x1="25" y1="35" x2="45" y2="35" />
                </g>

                <!-- Subtle highlight -->
                <ellipse
                    cx="42"
                    cy="35"
                    rx="15"
                    ry="8"
                    fill="rgba(255,255,255,0.15)"
                />
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
                    'font-bold leading-none text-gray-900 dark:text-white',
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
