import { createRouter, createWebHistory } from 'vue-router';
import PageView from '../views/PageView.vue';

const routes = [
  { path: '/', component: PageView },
  { path: '/:slug', component: PageView, props: true },
];

export default createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
