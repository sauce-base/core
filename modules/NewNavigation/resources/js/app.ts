import '../css/style.css';

/**
 * NewNavigation module setup
 * Called during app initialization before mounting
 */
export function setup() {
    console.log('NewNavigation module loaded');
}

/**
 * NewNavigation module after mount logic
 * Called after the app has been mounted
 */
export function afterMount() {
    console.log('NewNavigation module after mount logic executed');
}
