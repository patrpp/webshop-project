<template>
  <Header />
  <main class="pt-20 px-4">
    <div class="min-h-screen bg-gray-100 p-6">
      <router-view />
    </div>
  </main>

  <footer class="fixed bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow-sm md:flex md:items-center md:justify-between md:p-6 light:bg-gray-800 light:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
    <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
      <li><a href="#" class="hover:underline">Kapcsolat</a></li>
    </ul>
  </footer>
</template>

<script setup>
import Header from './components/Header.vue'
</script>
