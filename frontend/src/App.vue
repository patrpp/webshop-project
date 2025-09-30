<template>
  <div class="min-h-screen flex flex-col">
    <Header />

    <main class="flex-grow pt-20 px-4 bg-gray-100 p-6">
      <router-view />
      <Toast
        v-model:visible="toast.visible"
        :message="toast.message"
        :type="toast.type"
        :duration="toast.duration"
      />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import '@/style.css'
import Toast from '@/components/Toast.vue'
import { useToastStore } from '@/stores/toastStore'

const toast = useToastStore()
</script>
