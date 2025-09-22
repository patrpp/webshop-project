import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    host: '0.0.0.0',  // Expose to all network interfaces
    port: 5173,
    watch: {
      usePolling: true,     // Így biztosabb lesz, hogy érzékeli a fájl módosításokat
      interval: 1000,       // Polling intervallum ms-ban (opcionális)
    }
  },
})
