<script setup lang="ts">
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { usePomodoroStore } from "../stores/";

const title = "Pomodoro Module";
const message = "Welcome to the " + title;
const pomodoroStore = usePomodoroStore();
</script>

<template>
  <Head :title="title" />
  <AuthenticatedLayout
    :title="title"
    :breadcrumbs="[{ url: '/', label: 'Dashboard' }, { label: title }]"
  >
    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
          🍅 {{ message }}
        </h2>

        <p class="mt-2 text-gray-600 dark:text-gray-400 text-4xl font-mono">
          {{ pomodoroStore.minutes.toString().padStart(2, "0") }}:{{
            pomodoroStore.seconds.toString().padStart(2, "0")
          }}
        </p>

        <div class="mt-4 space-x-2">
          <button
            @click="pomodoroStore.start"
            class="rounded bg-green-500 px-5 py-3 text-white hover:bg-green-600"
          >
            ▶ Start
          </button>
          <button
            @click="pomodoroStore.pause"
            class="rounded bg-yellow-500 px-5 py-3 text-white hover:bg-yellow-600"
          >
            ⏸ Pause
          </button>
          <button
            @click="pomodoroStore.reset"
            class="rounded bg-gray-500 px-5 py-3 text-white hover:bg-gray-600"
          >
            🔄 Reset
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
