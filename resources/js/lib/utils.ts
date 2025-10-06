import { type ClassValue, clsx } from 'clsx';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { twMerge } from 'tailwind-merge';
import { DefineComponent } from 'vue';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

/**
 * Resolve a Vue component, supporting modular namespaces.
 *
 * @param name The name of the component to resolve. Can include module namespace like 'ModuleName::ComponentPath'.
 * @returns A Promise that resolves to the Vue component.
 */
export const resolveModularComponent = (name: string) => {
    if (name.includes('::')) {
        const [moduleName, componentPath] = name.split('::', 2);
        const moduleComponentPath = `../../modules/${moduleName}/resources/js/pages/${componentPath}.vue`;

        const moduleGlobs = import.meta.glob<DefineComponent>(
            '../../modules/*/resources/js/**/*.vue',
        );

        return resolvePageComponent(moduleComponentPath, moduleGlobs);
    }

    return resolvePageComponent(
        `./pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./pages/**/*.vue'),
    );
};

/**
 * Resolve and load a language JSON file for i18n.
 *
 * @param lang The language code to resolve (e.g., 'en', 'fr').
 * @returns The language JSON object.
 */
export const resolveLanguage = (lang: string) => {
    const langs = import.meta.glob('../../lang/*.json', {
        eager: true,
    }) as Record<string, { default: any }>;
    return langs[`../../lang/${lang}.json`]?.default || {};
};
