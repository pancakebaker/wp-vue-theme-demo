<!-- src/components/Header.vue -->
<template>
    <header class="g-yellow-200 shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo -->
            <Logo />

            <!-- Desktop Nav -->
            <nav class="hidden md:flex space-x-6">
                <ul class="flex space-x-6">
                    <li v-for="item in menuItems" :key="item.id" class="relative group">
                        <router-link :to="normalizeUrl(item.url)" class="text-gray-700 hover:text-indigo-600">
                            {{ item.title }}
                        </router-link>

                        <!-- Submenu -->
                        <ul v-if="item.children && item.children.length"
                            class="absolute left-0 mt-2 w-40 bg-white border rounded shadow-md opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 invisible group-hover:visible">
                            <li v-for="child in item.children" :key="child.id" class="border-b last:border-b-0">
                                <router-link :to="normalizeUrl(child.url)"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">
                                    {{ child.title }}
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>


            <!-- Mobile Menu Button -->
            <button @click="isOpen = !isOpen" class="md:hidden text-gray-700 focus:outline-none">
                <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Nav -->
        <div v-show="isOpen" class="md:hidden px-4 pb-4">
            <nav>
                <ul class="flex flex-col space-y-2">
                    <li v-for="item in menuItems" :key="item.id">
                        <div class="flex items-center justify-between">
                            <a :href="item.url" class="text-gray-700 hover:text-indigo-600">
                                {{ item.title }}
                            </a>
                            <button v-if="item.children" @click="toggleSubmenu(item.id)" class="text-gray-500">
                                ▼
                            </button>
                        </div>

                        <ul v-if="isSubmenuOpen(item.id)" class="ml-4 mt-2 space-y-1">
                            <li v-for="child in item.children" :key="child.id">
                                <a :href="child.url" class="text-gray-600 hover:text-indigo-600">
                                    {{ child.title }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>


    </header>
</template>

<script setup>
import Logo from './Logo.vue';
import { ref, onMounted } from 'vue';

const isOpen = ref(false);
const menuItems = ref([]);
const openSubmenus = ref({});

function normalizeUrl(url) {
    try {
        const siteOrigin = window.location.origin;
        return url.replace(siteOrigin, '') || '/';
    } catch {
        return '/';
    }
}

function toggleSubmenu(id) {
  openSubmenus.value[id] = !openSubmenus.value[id];
}

function isSubmenuOpen(id) {
  return openSubmenus.value[id] === true;
}

onMounted(() => {
    menuItems.value = window.demoThemeData?.primaryMenu ?? [];
});
</script>
