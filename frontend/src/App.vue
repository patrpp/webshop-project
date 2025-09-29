<template>
  <Header />

  <main class="flex-grow pt-20 px-4 bg-gray-100 p-6">
    <router-view />
  </main>

  <Footer />
</template>

<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
</script>
