import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const usePomodoroStore = defineStore(
    'modules/pomodoro',
    () => {
        const duration = ref(25 * 60); // 25 minutes in seconds
        const timeLeft = ref(duration.value);
        const isRunning = ref(false);
        let interval: number | null = null;

        const minutes = computed(() => Math.floor(timeLeft.value / 60));
        const seconds = computed(() => timeLeft.value % 60);

        const start = () => {
            if (isRunning.value) return;
            isRunning.value = true;
            interval = setInterval(() => {
                if (timeLeft.value > 0) {
                    timeLeft.value--;
                } else {
                    stop();
                }
            }, 1000);
        };

        const pause = () => {
            if (interval) clearInterval(interval);
            isRunning.value = false;
        };

        const reset = () => {
            pause();
            timeLeft.value = duration.value;
        };

        const stop = () => {
            pause();
            timeLeft.value = 0;
        };

        return {
            duration,
            timeLeft,
            minutes,
            seconds,
            isRunning,
            start,
            pause,
            reset,
            stop,
        };
    });
