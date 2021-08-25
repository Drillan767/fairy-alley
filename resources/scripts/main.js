import {createApp, h, resolveComponent} from 'vue';
import App from '@/views/App.vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import axios from 'axios';
import _ from 'lodash';
import Macy from 'macy';
import '../sass/app.scss';

createApp(App).mount('#app')

// -------------


// import 'dynamic-import-polyfill';

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const form = document.getElementById('contact');
if (form) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const feedback = document.querySelector('.feedback')
        feedback.classList.add('hidden');

        let formData = new FormData();
        ['name', 'email', 'subject', 'message'].forEach((entry) => {
            formData.append(entry, document.getElementById(entry).value)
        })

        if (document.getElementById('newsletter')?.checked) {
            formData.append('newsletter', '1')
        }

        axios.post('/contact', formData)
            .then((response) => {
                feedback.classList.remove('hidden');
                feedback.classList.add('bg-green-100',  'border-green-500', 'text-green-700');
                feedback.querySelector('p').innerHTML = response.data;
                form.reset();
            })
            .catch((error) => {
                if (error.response) {
                    feedback.classList.remove('hidden');
                    feedback.classList.add('bg-red-100',  'border-red-500', 'text-red-700');
                    const errors = error.response.data.errors
                    let messages = '<ul>'
                    for (const property in errors) {
                        messages += `<li>${property}: ${errors[property]}</li>`;
                    }

                    messages += '</li>';
                    feedback.querySelector('p').innerHTML = messages;
                }
            })
    })
}

const macy = document.getElementById('macy');

if (macy) {
    Macy({
        container: '#macy',
        trueOrder: false,
        waitForImages: false,
        margin: 24,
        columns: 4,
        breakAt: {
            1200: 3,
            520: 2,
            400: 1
        }
    });
}


if (document.getElementById('app')) {
    const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => require(`./Pages/${name}.vue`),
        setup({ el, app, props, plugin }) {
            return createApp({ render: () => h(app, props) })
                .use(plugin)
                .mixin({ methods: { route } })
                .mount(el);
        },
    });

    InertiaProgress.init({ color: '#4B5563' });
}
