import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  plugins: [vue()],
  build: {
    manifest: 'manifest.json', // this puts it in /dist
    outDir: 'dist',
    emptyOutDir: true,
    rollupOptions: {
      input: './src/main.ts',
      output: {
        // Flatten files into dist/ instead of dist/assets/
        entryFileNames: '[name]-[hash].js',
        chunkFileNames: '[name]-[hash].js',
        assetFileNames: '[name]-[hash][extname]',
      },
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
});
