export type RoleValue = 'admin' | 'editor' | 'author' | 'user';

export interface Role {
    value: RoleValue;
    label: string;
}

// Extended User for admin contexts with flattened role
export interface UserWithRole extends User {
    role: {
        name: RoleValue;
        label: string;
    };
    created_at: string;
}

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
    roles?: Array<{
        id: number;
        name: string;
        guard_name: string;
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
