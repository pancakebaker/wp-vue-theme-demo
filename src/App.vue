<!-- src/App.vue -->
<template>
  <div id="app-wrapper" class="min-h-screen flex flex-col">
    <PageLoader :visible="isLoading" />
    <Header />
    <main class="flex-grow">
      <router-view @hook:beforeRouteUpdate="startLoading" @hook:beforeRouteEnter="startLoading" />
      <div class="text-center p-10">
        <h1 class="text-4xl font-bold text-indigo-600">Welcome to Demo Theme</h1>
        <p class="mt-4 text-gray-600">Vue 3 + Tailwind CSS v4</p>
      </div>
    </main>
    <Footer />
  </div>
</template>

<script setup lang="ts">
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import PageLoader from './components/PageLoader.vue';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const isLoading = ref(false);
const router = useRouter();

const startLoading = () => {
  isLoading.value = true;
};

onMounted(() => {
  router.beforeEach((to, from, next) => {
    isLoading.value = true;
    next();
  });

  router.afterEach(() => {
    // Small delay to prevent flash
    setTimeout(() => {
      isLoading.value = false;
    }, 300);
  });
});
</script>
