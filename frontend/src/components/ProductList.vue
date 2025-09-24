<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <div
      v-for="product in products"
      :key="product.id"
      class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200"
    >
      <img
        v-if="product.image_url"
        :src="product.image_url"
        alt="Product image"
        class="w-full h-48 object-cover"
      />
      <div class="p-4">
        <h2 class="text-lg font-semibold">{{ product.name }}</h2>
        <p class="text-sm text-gray-600">{{ product.category }}</p>
        <p class="text-xl font-bold text-blue-600 mt-2">{{ product.price }} Ft</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products')
    products.value = response.data
  } catch (error) {
    console.error('Hiba a termékek betöltésekor:', error)
  }
})
</script>
