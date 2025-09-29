<template>
  <div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- Szűrők -->
    <div class="flex flex-col md:flex-row md:items-center md:gap-4 space-y-4 md:space-y-0">
      <input
        type="text"
        v-model="query"
        placeholder="Keresés név vagy leírás alapján"
        class="w-full md:w-1/3 border px-3 py-2 rounded"
      />

      <select v-model="season" class="w-full md:w-1/3 border px-3 py-2 rounded">
        <option value="">Évszak</option>
        <option value="nyári">Nyári</option>
        <option value="téli">Téli</option>
        <option value="4 évszakos">4 évszakos</option>
      </select>

      <select v-model.number="diameter" class="w-full md:w-1/3 border px-3 py-2 rounded">
        <option value="">Átmérő</option>
        <option v-for="d in diameters" :key="d" :value="d">{{ d }}"</option>
      </select>
      <button
        @click="clearFilters"
        class="w-full md:w-auto px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
      >
        Szűrők törlése
      </button>
    </div>

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
              class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
              Kosárba
            </button>
          </div>
        </div>
      </router-link>
    </div>
    <div class="flex justify-center items-center gap-2 mt-6" v-if="totalPages > 1">
      <button
        @click="page--"
        :disabled="page === 1"
        class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
      >
        Előző
      </button>

      <span>Oldal {{ page }} / {{ totalPages }}</span>

      <button
        @click="page++"
        :disabled="page === totalPages"
        class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
      >
        Következő
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const products = ref<any[]>([])

const query = ref('')
const season = ref('')
const diameter = ref<number | ''>('')
const diameters = [13, 14, 15, 16, 17, 18, 19, 20]

const page = ref(1)
const limit = 24
const totalPages = ref(1)

const cart = useCartStore()
const route = useRoute()
const router = useRouter()

async function loadProducts() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products/filter', {
      params: {
        q: query.value,
        season: season.value,
        diameter: diameter.value || undefined,
        page: page.value,
        limit: limit,
      },
    })
    products.value = response.data.data
    totalPages.value = response.data.total_pages
  } catch (error) {
    console.error('Hiba a termékek lekérésekor:', error)
  }
}

watch([query, season, diameter], () => {
  if (page.value !== 1) {
    page.value = 1 
  } else {
    updateRoute()
  }
})

watch(page, () => {
  updateRoute()
})

function updateRoute() {
  router.replace({
    path: '/products',
    query: {
      ...(query.value ? { q: query.value } : {}),
      ...(season.value ? { season: season.value } : {}),
      ...(diameter.value ? { diameter: diameter.value.toString() } : {}),
      ...(page.value !== 1 ? { page: page.value.toString() } : {}),
    },
  })
}

watch(
  () => route.query,
  () => {
    query.value = (route.query.q as string) || ''
    season.value = (route.query.season as string) || ''
    diameter.value = route.query.diameter ? Number(route.query.diameter) : ''
    page.value = route.query.page ? Number(route.query.page) : 1
    loadProducts()
  },
  { immediate: true },
)
function clearFilters() {
  query.value = ''
  season.value = ''
  diameter.value = ''
  page.value = 1

  router.replace({
    path: '/products',
    query: {},
  })
}

async function addToCart(product: any) {
  try {
    await cart.addToCart({
      id: product.id,
      name: product.name,
      price: product.price,
      image: product.image_url,
    })
    alert(`${product.name} hozzáadva a kosárhoz!`)
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
  }
}
</script>
