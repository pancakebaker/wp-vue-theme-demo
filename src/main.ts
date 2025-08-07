import { createApp } from 'vue';
import App from './App.vue';
import router from './router'

import './assets/css/app.css';
import './assets/css/contact-form-7.css'

window.addEventListener('DOMContentLoaded', () => {
    const mountTarget = document.querySelector('#vue-app')

    if (mountTarget) {
        mountTarget.innerHTML = ''; // Important to clear to avoid DOM mismatch
        createApp(App).use(router).mount('#vue-app')

        router.afterEach(() => {
            setTimeout(() => {
                const forms = document.querySelectorAll<HTMLFormElement>('.wpcf7 form');
                forms.forEach(form => {
                    if (window.wpcf7?.init) {
                        window.wpcf7.init(form);
                    }
                });
            }, 300);
        });

    } else {
        console.error('#vue-app not found — Vue not mounted')
    }
});
