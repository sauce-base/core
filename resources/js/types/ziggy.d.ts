declare module '../../vendor/tightenco/ziggy' {
    interface Config {
        routes: Record<string, any>;
        url: string;
        port: number | null;
        defaults: Record<string, any>;
    }

    export const Ziggy: Config;
    export const ZiggyVue: any;
}
