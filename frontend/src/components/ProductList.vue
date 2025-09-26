<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <router-link
      v-for="product in products"
      :key="product.id"
      :to="`/products/${product.id}`"
      class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[550px] flex flex-col no-underline text-inherit"
      @click.native.stop 
    >
      <img
        v-if="product.image_url"
        :src="product.image_url"
        alt="Product image"
        class="w-full h-48 object-cover flex-grow"
      />
      <div class="p-4 mt-auto">
        <h2 class="text-lg font-semibold">{{ product.name }}</h2>
        <p class="text-sm text-gray-600">{{ product.category }}</p>
        <div class="mt-2 flex items-center justify-between">
          <div>
            <p class="text-xl font-bold text-red-700">{{ product.price }} Ft</p>
            <p class="text-sm text-gray-500">{{ product.net_price }} Ft + ÁFA</p>
          </div>

          <button
            @click.stop.prevent="addToCart(product)"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          >
            Kosárba
          </button>
        </div>
      </div>
    </router-link>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'

const cart = useCartStore()
const products = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products')
    products.value = response.data
  } catch (error) {
    console.error('Hiba a termékek betöltésekor:', error)
  }
})

async function addToCart(product) {
  try {
    await cart.addToCart({
      id: product.id,
      name: product.name,
      price: product.price,
      image: product.image_url
    })
    alert(`${product.name} hozzáadva a kosárhoz! 🛒`)
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
  }
}
</script>

