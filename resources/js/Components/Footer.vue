<script setup lang="ts">
import IconMail from '~icons/heroicons/envelope';
import IconHeart from '~icons/heroicons/heart-solid';
import IconGithub from '~icons/mdi/github';
import IconTwitter from '~icons/mdi/twitter';
</script>

<template>
    <footer
        class="mt-16 w-full bg-gray-900/5 backdrop-blur-sm dark:bg-gray-100/5"
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
                        <IconGithub class="h-5 w-5" />
                    </a>

                    <!-- Twitter/X placeholder -->
                    <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="Follow on Twitter"
                    >
                        <IconTwitter class="h-5 w-5" />
                    </a>

                    <!-- Email placeholder -->
                    <a
                        href="mailto:hello@tadone.com"
                        class="inline-flex items-center justify-center rounded-full p-3 text-gray-600 transition-colors hover:bg-gray-100/50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200"
                        title="Send Email"
                    >
                        <IconMail class="h-5 w-5" />
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
                            <IconHeart class="h-4 w-4 text-red-500" />
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
