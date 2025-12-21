import * as LucideIcons from 'lucide-vue-next';
import type { Component } from 'vue';

/**
 * Resolve a Lucide icon name to its Vue component.
 *
 * Converts kebab-case icon names (e.g., 'square-terminal') to
 * PascalCase component names (e.g., 'SquareTerminal') and returns
 * the corresponding Lucide Vue component.
 *
 * @param iconName - The kebab-case icon name (e.g., 'square-terminal')
 * @returns The Lucide Vue component, or null if not found
 *
 * @example
 * const Icon = resolveIcon('square-terminal');
 * // Returns SquareTerminal component
 */
export function resolveIcon(
    iconName: string | null | undefined,
): Component | null {
    if (!iconName) return null;

    // Convert kebab-case to PascalCase: 'square-terminal' -> 'SquareTerminal'
    const componentName = iconName
        .split('-')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join('');

    const icon = (LucideIcons as unknown as Record<string, Component>)[
        componentName
    ];

    if (!icon && import.meta.env.DEV) {
        console.warn(`Icon not found: ${iconName} (${componentName})`);
    }

    return icon || null;
}
