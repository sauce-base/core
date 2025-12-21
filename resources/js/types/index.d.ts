export interface User {
    id: number;
    name: string;
    email: string;
    avatar: string;
    last_login_at: string | null;
}

export interface Breadcrumb {
    label: string;
    url: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    locale?: string;
    locales?: Record<string, string>;
    navigation?: Record<string, any>;
    breadcrumbs?: Breadcrumb[];
};
