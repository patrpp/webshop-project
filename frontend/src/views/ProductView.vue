<template>
  <div class="max-w-4xl mx-auto p-6">
    <div v-if="product" class="bg-white rounded shadow p-6 flex flex-col md:flex-row gap-6">
      <img
        :src="product.image_url || '/tires.png'"
        alt="Product image"
        class="w-full md:w-1/2 object-cover rounded"
      />
      <div class="md:w-1/2">
        <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
        <p class="text-sm text-gray-500 mb-4">{{ product.category }}</p>
        <p class="text-xl font-semibold text-red-700 mb-4">{{ $formatPrice(product.price) }} Ft</p>
        <p class="text-sm text-gray-500 mb-4">{{ $formatPrice(product.net_price) }} Ft + ÁFA</p>
        <p class="mb-6 text-gray-700" v-if="product.description">{{ product.description }}</p>

        <button @click="addToCart(product)" class="btn-grad">Kosárba</button>
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
import { useToastStore } from '@/stores/toastStore'

const route = useRoute()
const cart = useCartStore()
const toast = useToastStore()

const product = ref(null)

onMounted(async () => {
  try {
    const { id } = route.params
    const response = await axios.get(`/products/${id}`)
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
    toast.show(`${prod.name} hozzáadva a kosárhoz!`, 'success')
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
    toast.show('Hiba történt a kosárba helyezéskor.', 'error')
  }
}
</script>
