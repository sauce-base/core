/**
 * Navigation type definitions.
 *
 * Simplified navigation structure using convention-based config files.
 */

/**
 * Navigation prop structure (shared via Inertia).
 */
export interface Navigation {
    app: MenuItem[];
    settings: MenuItem[];
    user: MenuItem[];
}

/**
 * Menu item.
 */
export interface MenuItem {
    id?: string;
    label: string;
    route?: string; // Laravel route name (for active state matching)
    url?: string; // Generated URL (for navigation)
    icon?: string; // Lucide icon name
    permission?: string; // Server-side filtered
    action?: string; // For action buttons
    type?: 'label' | 'separator';
    active?: boolean; // Server-side active state from Spatie
    children?: MenuItem[];
}
