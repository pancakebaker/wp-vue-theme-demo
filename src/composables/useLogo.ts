// composables/useLogo.ts
import { ref, onMounted } from 'vue';
import axios from 'axios';

export function useLogo() {
  const logo = ref<{ url: string; alt: string }>({ url: '', alt: '' });

  onMounted(async () => {
    try {
      const response = await axios.get('/wp-json/demo-theme/v1/logo');
      logo.value = response.data;
    } catch (err) {
      console.warn('Logo not found or error fetching:', err);
    }
  });

  return { logo };
}
