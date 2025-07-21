<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { getProviderUIConfig } from '@/lib/socialProviders';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface ConnectedAccount {
    provider: string;
    last_login_at: string | null;
    provider_avatar_url: string | null;
}

const page = usePage();
const user = computed(() => page.props.auth.user as any);
const connectedAccounts = computed(() => user.value?.connected_providers || []);

// Get available providers that aren't connected yet
const availableProviders = computed(() => {
    const connected = connectedAccounts.value.map(
        (account: ConnectedAccount) => account.provider,
    );
    const allProviders = ['google', 'github', 'facebook']; // Could be dynamic from config
    return allProviders.filter((provider) => !connected.includes(provider));
});

const formatDate = (dateString: string | null) => {
    if (!dateString) return 'Never';
    return new Date(dateString).toLocaleDateString();
};

const disconnect = (provider: string) => {
    if (connectedAccounts.value.length === 1 && !user.value.password) {
        alert(
            'Cannot disconnect your only login method. Set a password first.',
        );
        return;
    }

    if (
        confirm(`Are you sure you want to disconnect your ${provider} account?`)
    ) {
        router.delete(route('social.disconnect', provider), {
            preserveScroll: true,
        });
    }
};

const connectProvider = (provider: string) => {
    window.location.href = `/auth/${provider}`;
};
</script>

<template>
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Connected Accounts
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage your social login providers and choose your preferred
                avatar.
            </p>
        </div>

        <!-- Connected Providers -->
        <div v-if="connectedAccounts.length > 0" class="space-y-4">
            <div
                v-for="account in connectedAccounts"
                :key="account.provider"
                class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700"
            >
                <div class="flex items-center space-x-4">
                    <!-- Provider Icon -->
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800"
                    >
                        <component
                            :is="getProviderUIConfig(account.provider).icon"
                            class="h-6 w-6"
                        />
                    </div>

                    <!-- Provider Info -->
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ getProviderUIConfig(account.provider).name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Last used: {{ formatDate(account.last_login_at) }}
                        </p>
                    </div>

                    <!-- Avatar Preview -->
                    <div v-if="account.provider_avatar_url" class="ml-4">
                        <img
                            :src="account.provider_avatar_url"
                            :alt="`${account.provider} avatar`"
                            class="h-8 w-8 rounded-full"
                        />
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="disconnect(account.provider)"
                        :disabled="
                            connectedAccounts.length === 1 && !user.password
                        "
                    >
                        Disconnect
                    </Button>
                </div>
            </div>
        </div>

        <!-- No Connected Accounts -->
        <div v-else class="py-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">
                No social accounts connected yet.
            </p>
        </div>

        <!-- Connect Additional Providers -->
        <div v-if="availableProviders.length > 0" class="space-y-4">
            <div>
                <h4
                    class="text-md font-medium text-gray-900 dark:text-gray-100"
                >
                    Connect More Accounts
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Link additional social accounts for easier login options.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <Button
                    v-for="provider in availableProviders"
                    :key="provider"
                    variant="outline"
                    size="sm"
                    @click="connectProvider(provider)"
                    class="flex items-center space-x-2"
                >
                    <component
                        :is="getProviderUIConfig(provider).icon"
                        class="h-4 w-4"
                    />
                    <span
                        >Connect {{ getProviderUIConfig(provider).name }}</span
                    >
                </Button>
            </div>
        </div>
    </div>
</template>
