<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'

const cart = useCartStore()

const products = ref<Product[]>([])
const randomProducts = ref<Product[]>([])
interface Product {
  id: number
  name: string
  price: number
  image_url: string
  net_price: number
  category: string
}

function getRandomProducts(allProducts: Product[], count: number): Product[] {
  const shuffled = [...allProducts].sort(() => 0.5 - Math.random())
  return shuffled.slice(0, count)
}

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products')
    products.value = response.data
    randomProducts.value = getRandomProducts(products.value, 4)
  } catch (error) {
    console.error('Hiba a termékek betöltésekor:', error)
  }
})

async function addToCart(product: Product) {
  try {
    await cart.addToCart({
      id: product.id,
      name: product.name,
      price: product.price,
      image: '',
    })
    triggerCartToast(`${product.name} sikeresen hozzáadva a kosárhoz!`)
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
    showCartErrorToast.value = true
    setTimeout(() => {
      showCartErrorToast.value = false
    }, 3000)
    triggerCartErrorToast(`Hiba történt: ${error}`)
  }
}

const showCartToast = ref(false)
const showCartErrorToast = ref(false)
const cartToastMessage = ref('')

function triggerCartToast(message: string) {
  cartToastMessage.value = message
  showCartToast.value = true
  setTimeout(() => {
    showCartToast.value = false
  }, 3000)
}

function triggerCartErrorToast(message: string) {
  cartToastMessage.value = message
  showCartErrorToast.value = true
  setTimeout(() => {
    showCartErrorToast.value = false
  }, 3000)
}

</script>

<template>
 <transition name="fade">
  <div
    v-if="showCartToast"
    class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-md z-50"
  >
    {{ cartToastMessage }}
  </div>
</transition>


<transition name="fade">
  <div
    v-if="showCartErrorToast"
    class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-md z-50"
  >
    {{ cartToastMessage }}
  </div>
</transition>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <router-link
      v-for="product in randomProducts"
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
        <div class="mt-2 flex items-center justify-between">
          <div>
            <p class="text-xl font-bold text-red-700">{{ $formatPrice(product.price) }} Ft</p>
            <p class="text-sm text-gray-500">{{ $formatPrice(product.net_price) }} Ft + ÁFA</p>
          </div>
          <button @click.stop.prevent="addToCart(product)" class="btn-grad">Kosárba</button>
        </div>
      </div>
    </router-link>
  </div>
</template>
