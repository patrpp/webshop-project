<template>
  <div class="space-y-4">
    <input
      type="text"
      v-model="query"
      placeholder="Keresés név vagy leírás alapján"
      class="w-full border px-3 py-2 rounded"
    />

    <select v-model="season" class="w-full border px-3 py-2 rounded">
      <option value="">Évszak (mind)</option>
      <option value="nyári">Nyári</option>
      <option value="téli">Téli</option>
      <option value="4 évszakos">4 évszakos</option>
    </select>

    <select v-model="diameter" class="w-full border px-3 py-2 rounded">
      <option value="">Átmérő (mind)</option>
      <option v-for="d in diameters" :key="d" :value="d">{{ d }}"</option>
    </select>

    <button @click="loadProducts" class="bg-blue-500 text-white px-4 py-2 rounded">
      Keresés
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="p in products" :key="p.id" class="border rounded p-4">
        <img :src="p.image_url" alt="" class="w-full h-40 object-contain mb-2" />
        <h3 class="font-bold">{{ p.name }}</h3>
        <p>{{ p.description }}</p>
        <p class="text-sm text-gray-600">{{ p.category }}</p>
        <p class="text-lg font-semibold">{{ p.price }} Ft</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { fetchProducts, Product } from '@/services/productService'

const query = ref('')
const season = ref('')
const diameter = ref<number | ''>('')

const diameters = [13, 14, 15, 16, 17, 18, 19, 20]

const products = ref<Product[]>([])

async function loadProducts() {
  const filters = {
    q: query.value,
    season: season.value || undefined,
    diameter: diameter.value ? Number(diameter.value) : undefined
  }

  products.value = await fetchProducts(filters)
}

loadProducts() // automatikus betöltés induláskor
</script>
