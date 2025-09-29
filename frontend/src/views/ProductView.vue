<template>
  <div class="max-w-4xl mx-auto p-6">
    <div v-if="product" class="bg-white rounded shadow p-6 flex flex-col md:flex-row gap-6">
      <img
        v-if="product.image_url"
        :src="product.image_url"
        alt="Product image"
        class="w-full md:w-1/2 object-cover rounded"
      />
      <div class="md:w-1/2">
        <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
        <p class="text-gray-600 mb-4">{{ product.category }}</p>
        <p class="text-xl font-semibold text-red-700 mb-4">{{ product.price }} Ft</p>
        <p class="mb-6 text-gray-700" v-if="product.description">{{ product.description }}</p>

        <button
          @click="addToCart(product)"
          class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Kosárba
        </button>
      </div>
    </div>

    <p v-else>Betöltés...</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'

const route = useRoute()
const cart = useCartStore()

const product = ref(null)

onMounted(async () => {
  try {
    const { id } = route.params
    const response = await axios.get(`http://127.0.0.1:8000/api/products/${id}`)
    product.value = response.data
  } catch (error) {
    console.error('Hiba a termék betöltésekor:', error)
  }
})

async function addToCart(prod) {
  try {
    await cart.addToCart({
      id: prod.id,
      name: prod.name,
      price: prod.price,
      image: prod.image_url,
    })
    alert(`${prod.name} hozzáadva a kosárhoz!`)
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
  }
}
</script>
