<script setup lang="ts">
import IconCode from '~icons/heroicons/code-bracket';
import IconMail from '~icons/heroicons/envelope';
import IconGithub from '~icons/mdi/github';
import IconTwitter from '~icons/mdi/twitter';
</script>

<template>
    <footer class="mt-auto border-t border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/50">
        <div class="mx-auto max-w-7xl px-6 py-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Company Info -->
                <div class="text-center md:text-left">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                        Tadone
                    </h3>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        Simple task management for productive teams and individuals.
                    </p>
                </div>

                <!-- Links -->
                <div class="text-center">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                        Connect
                    </h3>
                    <div class="flex justify-center gap-4">
                        <a
                            href="https://github.com/roble/tadone"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            title="GitHub"
                        >
                            <IconGithub class="h-5 w-5" />
                        </a>
                        <a
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            title="Twitter"
                        >
                            <IconTwitter class="h-5 w-5" />
                        </a>
                        <a
                            href="mailto:hello@tadone.com"
                            class="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            title="Email"
                        >
                            <IconMail class="h-5 w-5" />
                        </a>
                    </div>
                </div>

                <!-- Tech Stack -->
                <div class="text-center md:text-right">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                        Built with
                    </h3>
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400 md:justify-end">
                        <IconCode class="h-4 w-4" />
                        <span>Vue.js & Laravel</span>
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-800">
                <div class="flex flex-col items-center justify-between gap-4 text-sm text-gray-600 dark:text-gray-400 md:flex-row">
                    <p>© 2025 Tadone. All rights reserved.</p>
                    <p>v1.0.0</p>
                </div>
            </div>
        </div>
    </footer>
</template>
