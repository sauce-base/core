import '../css/style.css';

/**
 * {Module} module setup
 * Called during app initialization before mounting
 */
export function setup() {
    console.log('{Module} module loaded');
}

/**
 * {Module} module after mount logic
 * Called after the app has been mounted
 */
export function afterMount() {
    console.log('{Module} module after mount logic executed');
}