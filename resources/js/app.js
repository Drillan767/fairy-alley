import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { contact } from "./Modules/landing";
import PrimeVue from 'primevue/config';
import mitt from 'mitt';
import 'dynamic-import-polyfill';
import titleMixin from './Modules/titleMixin';
import _ from 'lodash';
import axios from 'axios';
import '../sass/app.scss';

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = document.getElementById('app');
const emitter = mitt();
const pages = import.meta.glob('./Pages/**/*.vue');

if (document.getElementById('contact')) {
    contact();
}

const created = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => {
                const importPage = pages[`./Pages/${name}.vue`];
                if (!importPage) {
                    throw new Error(`Unknown page ${name}. Is it located under Pages with a .vue extension?`);
                }
                return importPage().then(module => module.default)
            }
        }),
})
    .mixin({ methods: { route } })
    .mixin(titleMixin)
    .use(InertiaPlugin)
    .use(PrimeVue);

created.config.globalProperties.emitter = emitter
created.mount(app);

InertiaProgress.init({ color: '#4B5563' });
