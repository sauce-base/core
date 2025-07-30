<script setup lang="ts">
import IconHeart from '~icons/heroicons/heart';
</script>

<template>
    <footer
        class="mt-auto border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950"
    >
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div
                class="flex flex-col items-center justify-between gap-4 text-sm text-gray-600 md:flex-row dark:text-gray-400"
            >
                <div class="flex items-center gap-6">
                    <span>© 2025 Sauce Base</span>
                    <a
                        href="https://github.com/roble/saucebase"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hover:text-gray-900 dark:hover:text-white"
                    >
                        GitHub
                    </a>
                    <a
                        href="#"
                        class="hover:text-gray-900 dark:hover:text-white"
                    >
                        Documentation
                    </a>
                </div>
                <div class="flex items-center gap-1">
                    <span>Made with</span>
                    <IconHeart class="h-4 w-4 text-red-500" />
                    <span>for developers</span>
                </div>
            </div>
        </div>
    </footer>
</template>
