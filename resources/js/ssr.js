import { createInertiaApp } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME || 'Aurelice';

createInertiaApp({
    title: (title) => (title ? `${title} – ${appName}` : appName),
    pages: './pages',
});
