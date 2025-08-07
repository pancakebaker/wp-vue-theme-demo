<!-- src/components/Footer.vue -->
<template>
    <footer class="bg-gray-900 text-white py-6">
        <div class="container mx-auto px-4">
            <nav class="flex flex-wrap justify-center space-x-6">
                <a v-for="item in menuItems" :key="item.ID" :href="item.url" class="hover:underline">
                    {{ item.title }}
                </a>
            </nav>
            <p class="text-center text-sm text-gray-400 mt-4">
                &copy; {{ new Date().getFullYear() }} Demo Theme. All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const menuItems = ref([]);

onMounted(async () => {
    menuItems.value = window.demoThemeData?.footerMenu ?? [];
})
</script>
