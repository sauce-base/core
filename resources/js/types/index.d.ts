export interface User {
    id: number;
    name: string;
    email: string;
    avatar_url?: string;
    avatar: string;
    last_login_at: string | null;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
