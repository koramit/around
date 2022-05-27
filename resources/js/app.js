// eslint-disable-next-line no-undef
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import i18nPlugin from './plugins/i18n';
import AppLayout from './Components/Layouts/AppLayout';
import PageLayout from './Components/Layouts/PageLayout';

InertiaProgress.init({
    delay: 200,
    color: '#AD9C68',
    showSpinner: true
});

createInertiaApp({
    resolve: name => {
        // eslint-disable-next-line no-undef
        const page = require(`./Pages/${name}`).default;
        if (page.layout === undefined) {
            page.layout = AppLayout;
        }

        if (page.props?.layout === null) {
            page.layout = PageLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(i18nPlugin);
        app.use(plugin).mount(el);
    },
});
