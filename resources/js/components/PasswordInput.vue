<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { Eye, EyeOff } from 'lucide-vue-next';
import type { HTMLAttributes } from 'vue';
import { ref } from 'vue';

const props = defineProps<{
    defaultValue?: string | number;
    modelValue?: string | number;
    class?: HTMLAttributes['class'];
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void;
}>();

defineOptions({
    inheritAttrs: false,
});

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <div class="relative">
        <Input
            :model-value="modelValue"
            :default-value="defaultValue"
            :type="showPassword ? 'text' : 'password'"
            :class="cn('pr-10', props.class)"
            @update:model-value="emits('update:modelValue', $event)"
            v-bind="$attrs"
        />
        <Button
            type="button"
            variant="ghost"
            size="icon"
            @click="togglePasswordVisibility"
            class="absolute inset-y-0 right-0 h-full px-3 py-2 hover:bg-transparent"
            :title="showPassword ? $t('Hide password') : $t('Show password')"
            :aria-label="showPassword ? $t('Hide password') : $t('Show password')"
            :data-testid="
                $attrs['data-testid']
                    ? `${$attrs['data-testid']}-toggle`
                    : 'password-toggle'
            "
        >
            <Eye v-if="!showPassword" class="text-muted-foreground h-4 w-4" />
            <EyeOff v-else class="text-muted-foreground h-4 w-4" />
        </Button>
    </div>
</template>
