<script setup lang="ts">
defineProps<{
    showBackground?: boolean;
}>();
</script>

<template>
    <footer
        :class="[
            'mt-16 w-full',
            showBackground
                ? 'bg-gray-900/5 backdrop-blur-sm dark:bg-gray-100/5'
                : '',
        ]"
    >
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="text-center">
                <!-- Social Links -->
                <div class="mb-6 flex justify-center gap-6">
                    <a
                        href="https://github.com/roble/tadone"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="View on GitHub"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                            />
                        </svg>
                    </a>

                    <!-- Twitter/X placeholder -->
                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="Follow on Twitter"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                            />
                        </svg>
                    </a>

                    <!-- Discord placeholder -->
                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="Join Discord"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419-.0002 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9554 2.4189-2.1568 2.4189Z"
                            />
                        </svg>
                    </a>

                    <!-- Email placeholder -->
                    <a
                        href="mailto:hello@tadone.com"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="Send Email"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                    </a>
                </div>

                <!-- Version and Copyright -->
                <div
                    class="border-t border-gray-200/50 pt-6 dark:border-gray-700/50"
                >
                    <div
                        class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between"
                    >
                        <div class="text-center sm:text-left">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Tadone v1.0.0 - Your productivity companion
                            </p>
                            <p
                                class="mt-1 text-xs text-gray-500 dark:text-gray-500"
                            >
                                © 2025 Tadone. All rights reserved.
                            </p>
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
                        >
                            <span>Made with</span>
                            <svg
                                class="h-4 w-4 text-red-500"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                />
                            </svg>
                            <span>using</span>
                            <span
                                class="font-semibold text-green-600 dark:text-green-400"
                                >Vue</span
                            >
                            <span>&</span>
                            <span
                                class="font-semibold text-red-600 dark:text-red-400"
                                >Laravel</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
