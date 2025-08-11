<script setup lang="ts">
import { Button } from '@/components/ui/button';
import InputLabel from '@/components/ui/form/FormLabel.vue';
import InputError from '@/components/ui/form/FormMessage.vue';
import TextInput from '@/components/ui/Input.vue';
import Modal from '@/components/ui/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $t('Delete Account') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{
                    $t(
                        'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
                    )
                }}
            </p>
        </header>

        <Button variant="destructive" @click="confirmUserDeletion">{{
            $t('Delete Account')
        }}</Button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    {{ $t('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.',
                        )
                    }}
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        :value="$t('Password')"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        :placeholder="$t('Password')"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <Button variant="secondary" @click="closeModal">
                        {{ $t('Cancel') }}
                    </Button>

                    <Button
                        variant="destructive"
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        {{ $t('Delete Account') }}
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
