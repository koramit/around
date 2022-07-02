// eslint-disable-next-line no-undef
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import i18nPlugin from './plugins/i18n';
import AppLayout from './Components/Layouts/AppLayout.vue';
import PageLayout from './Components/Layouts/PageLayout.vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

InertiaProgress.init({
    delay: 200,
    color: '#907326',
    showSpinner: true
});

createInertiaApp({
    resolve: name => {
        // eslint-disable-next-line no-undef
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
            .then(page => {
                // if (page.layout === undefined) {
                //     page.default.layout = AppLayout;
                // }
                // if (page.props?.layout === null) {
                //     page.default.layout = PageLayout;
                // }
                page.default.layout = name.startsWith('Auth/') ? PageLayout : AppLayout;


                return page;
            });
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(i18nPlugin);
        app.use(plugin).mount(el);
    },
});
