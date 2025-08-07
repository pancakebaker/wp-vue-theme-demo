// src/router.ts
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import Page from './components/Page.vue'

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'Home',
        component: Page,
        props: { slug: 'home' }, // static homepage slug
    },
    {
        path: '/:slug',
        name: 'Page',
        component: Page,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
