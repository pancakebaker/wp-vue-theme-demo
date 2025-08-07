<!-- src/components/PAgeLoader.vue -->
<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75" v-if="visible">
    <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ visible: boolean }>();
</script>
