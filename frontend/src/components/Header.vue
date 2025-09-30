<template>
  <header class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <router-link to="/">
            <img
              src="https://flotta.marso.hu/logos/marso_fekvo_logo.jpg"
              alt="Marso Logo"
              class="h-8 w-auto"
            />
          </router-link>
        </div>
        <!-- Menü gomb (mobil) -->
        <div class="md:hidden">
          <button @click="toggleMenu" class="text-gray-600 focus:outline-none">
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                :d="menuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
              />
            </svg>
          </button>
        </div>

        <!-- Menü (desktop) -->
        <nav class="hidden md:flex space-x-4">
          <router-link to="/" class="text-gray-700 hover:text-red-600">Főoldal</router-link>
          <router-link to="/products" class="text-gray-700 hover:text-red-600"
            >Termékek</router-link
          >
          <router-link to="/about" class="text-gray-700 hover:text-red-600">Rólunk</router-link>
          <router-link to="/contact" class="text-gray-700 hover:text-red-600"
            >Kapcsolat</router-link
          >
        </nav>
        <router-link to="/cart" class="relative inline-flex items-center">
          <span class="material-symbols-outlined text-8xl text-gray-800"> trolley </span>
          Kosár
          <span
            v-if="cart.items.length > 0"
            class="top-1 -right-2 bg-red-600 text-white rounded-full px-1.5 py-0.5 text-xs font-bold shadow"
          >
            {{ cart.items.length }}
          </span>
        </router-link>
      </div>
    </div>

    <!-- Mobil menü -->
    <div v-if="menuOpen" class="md:hidden bg-white border-t">
      <div class="px-4 py-2 space-y-2">
        <router-link to="/" class="block text-gray-700 hover:text-red-600">Főoldal</router-link>
        <router-link to="/products" class="block text-gray-700 hover:text-red-600"
          >Termékek</router-link
        >
        <router-link to="/about" class="block text-gray-700 hover:text-red-600">Rólunk</router-link>
        <router-link to="/contact" class="block text-gray-700 hover:text-red-600"
          >Kapcsolat</router-link
        >
        <router-link to="/cart" class="text-gray-700 hover:text-red-600 flex items-center">
          Kosár
          <span
            v-if="cart.items.length > 0"
            class="ml-2 bg-red-600 text-white rounded-full px-2 py-0.5 text-xs"
          >
            {{ cart.items.length }}
          </span>
        </router-link>
      </div>
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { useCartStore } from '@/stores/cartStore'

export default defineComponent({
  name: 'Header',
  setup() {
    const menuOpen = ref(false)
    const cart = useCartStore()

    const toggleMenu = () => {
      menuOpen.value = !menuOpen.value
    }

    return {
      menuOpen,
      toggleMenu,
      cart,
    }
  },
})
</script>
