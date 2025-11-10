<template>
  <div
    class="w-full mx-auto p-8 space-y-8 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 rounded-xl shadow-lg"
  >
    <div class="flex flex-col md:flex-row md:items-center md:gap-6 space-y-6 md:space-y-0">
      <div class="relative w-full md:w-1/3">
        <input
          type="text"
          v-model="query"
          placeholder="Keresés név vagy leírás alapján"
          class="w-full border border-gray-400 rounded-xl pl-12 pr-4 py-3 bg-white text-black placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-red-600 focus:border-red-600 transition shadow-md"
        />
        <div
          class="absolute left-4 top-1/2 transform -translate-y-1/2 pointer-events-none text-red-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-4.35-4.35A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"
            />
          </svg>
        </div>
      </div>

      <div class="relative w-full md:w-1/3">
        <span
          class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-red-600 pointer-events-none"
        >
          sunny_snowing
        </span>
        <select
          v-model="season"
          class="w-full border border-gray-400 rounded-xl pl-10 pr-5 py-3 bg-white text-black focus:outline-none focus:ring-4 focus:ring-red-600 focus:border-red-600 shadow-md transition appearance-none"
        >
          <option value="" disabled hidden>Szezon</option>
          <option value="nyári">Nyári</option>
          <option value="téli">Téli</option>
          <option value="4 évszakos">4 évszakos</option>
        </select>
      </div>

      <div class="relative w-full md:w-1/3">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="absolute left-4 top-1/2 transform -translate-y-1/2 text-red-600 pointer-events-none"
          height="24"
          width="24"
          fill="#dc2626"
          viewBox="0 -960 960 960"
        >
          <path
            d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Z"
          />
        </svg>
        <select
          v-model.number="diameter"
          class="w-full border border-gray-400 rounded-xl pl-12 pr-5 py-3 bg-white text-black focus:outline-none focus:ring-4 focus:ring-red-600 focus:border-red-600 shadow-md transition appearance-none"
        >
          <option value="" disabled hidden>Átmérő</option>
          <option v-for="d in diameters" :key="d" :value="d">{{ d }}</option>
        </select>
      </div>

      <button
        @click="clearFilters"
        class="w-full md:w-1/3 bg-red-700 hover:bg-red-800 text-white font-bold rounded-xl px-6 py-3 shadow-lg transition transform hover:scale-105"
      >
        Szűrők törlése
      </button>
    </div>

    <div class="flex flex-wrap gap-3">
      <button
        v-for="b in brands"
        :key="b"
        @click="toggleBrand(b)"
        type="button"
        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-sm font-semibold transition shadow-md focus:outline-none focus:ring-2 focus:ring-red-800"
        :class="
          brand.includes(b)
            ? 'bg-red-700 text-white border-red-900 hover:bg-red-900 shadow-lg'
            : 'bg-gray-800 text-white border-gray-700 hover:bg-gray-700'
        "
      >
        <span>{{ b }}</span>
        <span
          v-if="brand.includes(b)"
          class="text-white font-bold cursor-pointer select-none text-base leading-none"
          >×</span
        >
      </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <router-link
        v-for="product in products"
        :key="product.id"
        :to="`/products/${product.id}`"
        class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[550px] flex flex-col no-underline text-inherit"
        @click.stop
      >
        <img
          :src="product.image_url || '/tires.png'"
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

    <div class="flex justify-center items-center gap-2 mt-6" v-if="totalPages > 1">
      <button
        @click="page--"
        :disabled="page === 1"
        class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
      >
        <
      </button>
      <span>Oldal {{ page }} / {{ totalPages }}</span>
      <button
        @click="page++"
        :disabled="page === totalPages"
        class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
      >
        >
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useToastStore } from '@/stores/toastStore'
import { computed } from 'vue'

const products = ref<any[]>([])
const query = ref('')
const season = ref('')
const diameter = ref<number | ''>('')

const diameters = [13, 14, 15, 16, 17, 18, 19, 20]
const brand = ref<string[]>([])
const brands = ref<string[]>([])

const page = ref(1)
const limit = 24
const totalPages = ref(1)

const route = useRoute()
const router = useRouter()
const cart = useCartStore()
const toast = useToastStore()
const cartItems = computed(() => cart.items)

async function loadProducts() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/products/filter', {
      params: {
        q: query.value,
        season: season.value,
        diameter: diameter.value || undefined,
        brand: brand.value.length ? brand.value.join(',') : undefined,
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

onMounted(async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/products/brands')
    brands.value = res.data
  } catch (error) {
    console.error('Nem sikerült betölteni a márkákat:', error)
  }
  await loadProducts()
})

watch([query, season, diameter], () => {
  if (page.value !== 1) {
    page.value = 1
  } else {
    updateRoute()
  }
})

watch(page, updateRoute)

watch(
  () => route.query,
  () => {
    query.value = (route.query.q as string) || ''
    season.value = (route.query.season as string) || ''
    diameter.value = route.query.diameter ? Number(route.query.diameter) : ''
    brand.value = route.query.brand ? (route.query.brand as string).split(',') : []
    page.value = route.query.page ? Number(route.query.page) : 1
    loadProducts()
  },
  { immediate: true },
)

function updateRoute() {
  router.replace({
    path: '/products',
    query: {
      ...(query.value ? { q: query.value } : {}),
      ...(season.value ? { season: season.value } : {}),
      ...(diameter.value ? { diameter: diameter.value.toString() } : {}),
      ...(brand.value.length ? { brand: brand.value.join(',') } : {}),
      ...(page.value !== 1 ? { page: page.value.toString() } : {}),
    },
  })
}

function toggleBrand(selected: string) {
  if (brand.value.includes(selected)) {
    brand.value = brand.value.filter((b) => b !== selected)
  } else {
    brand.value.push(selected)
  }
  page.value = 1
  updateRoute()
}

function clearFilters() {
  query.value = ''
  season.value = ''
  diameter.value = ''
  brand.value = []
  page.value = 1
  updateRoute()
}

async function addToCart(product: { id: number,name: string }) {
  try {
    await cart.addToCart(product.id)

    toast.show(`${product.name} hozzáadva a kosárhoz!`, 'success')
  } catch (error) {
    console.error('Hiba a kosárba helyezéskor:', error)
    toast.show('Hiba történt a kosárba helyezéskor.', 'error')
  }
}
</script>
