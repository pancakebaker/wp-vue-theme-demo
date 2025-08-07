<!-- components/Logo.vue -->
<template>
  <div v-if="logo.url" class="shrink-0">
    <img :src="logo.url" :alt="logo.alt || 'Logo'" class="h-10" />
  </div>
</template>

<script setup lang="ts">
import { useLogo } from '@/composables/useLogo';
const { logo } = useLogo();
</script>
