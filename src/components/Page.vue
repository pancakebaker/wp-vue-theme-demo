<!-- src/components/Page.vue -->
<template>
    <div class="max-w-4xl mx-auto px-4 py-10">
        <!-- Show content only if page is available -->
        <div v-if="page">
            <h1 class="text-3xl font-bold mb-4">{{ page.title.rendered }}</h1>
            <div v-html="page.content.rendered" class="prose max-w-none"></div>
        </div>

        <!-- Loading state -->
        <div v-else-if="loading" class="text-gray-500">Loading...</div>

        <!-- Error state -->
        <div v-else class="text-red-500">Error loading page.</div>
    </div>
</template>
<script setup lang="ts">
import { ref, onMounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()

const page = ref<{
    title: { rendered: string };
    content: { rendered: string };
} | null>(null)

const loading = ref(false)
const error = ref(false)

const fetchPage = async () => {
    loading.value = true
    error.value = false
    const slug = route.params.slug || 'home'

    try {
        const res = await axios.get(`/wp-json/wp/v2/pages?slug=${slug}`)
        if (Array.isArray(res.data) && res.data.length > 0) {
            page.value = res.data[0]

            // Wait for DOM to update with new content before reinitializing CF7
            await nextTick()

            // Initialize any form now in the DOM
            setTimeout(() => {
                const forms = document.querySelectorAll<HTMLFormElement>('.wpcf7 form');

                forms.forEach(form => {
                    // Prevent re-initialization
                    if ((form as any)._cf7Initialized) return;

                    if (window.wpcf7?.init) {
                        window.wpcf7.init(form);
                        (form as any)._cf7Initialized = true; // ✅ mark as initialized
                    }
                });
            }, 300);


        } else {
            error.value = true
        }
    } catch (e) {
        console.error('Error fetching page:', e)
        error.value = true
    } finally {
        loading.value = false
    }
}

// Run on component mount
onMounted(fetchPage)

// Watch for route change (e.g., /contact-us → /about)
watch(() => route.params.slug, fetchPage)
</script>
