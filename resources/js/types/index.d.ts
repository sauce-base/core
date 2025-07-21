export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    avatar_url?: string;
    avatar: string; // Computed avatar with fallback
    connected_providers: Array<{
        provider: string;
        last_login_at: string | null;
        provider_avatar_url: string | null;
    }>;
    password?: string; // For checking if user has password
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
