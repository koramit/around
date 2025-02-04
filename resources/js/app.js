import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import i18nPlugin from './plugins/i18n';
import AppLayout from './Components/Layouts/AppLayout.vue';
import PageLayout from './Components/Layouts/PageLayout.vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
    progress: {
        delay: 200,
        color: '#907326',
        showSpinner: true
    },
    resolve: name => {
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
            .then(page => {
                page.default.layout = (name.startsWith('Auth/') || name.startsWith('Guest/') || name.includes('/Printout/') ) ? PageLayout : AppLayout;

                return page;
            });
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(i18nPlugin);
        app.use(plugin).mount(el);
    },
});
