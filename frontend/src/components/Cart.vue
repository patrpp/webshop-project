<script setup lang="ts">
import { onMounted } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import type { CartItem } from '@/stores/cartStore'

const cart = useCartStore()

onMounted(() => {
  cart.init()
})

function addSampleProduct() {
  cart.addToCart({ id: 1, name: 'Teszt termék', price: 999 })
}

// ide jönnek az increment és decrement függvények
function increment(item: CartItem) {
  cart.updateItemQuantity(item.id, item.quantity + 1)
}

function decrement(item: CartItem) {
  if (item.quantity > 1) {
    cart.updateItemQuantity(item.id, item.quantity - 1)
  }
}
</script>

<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Kosár</h2>

    <div v-if="cart.loading" class="text-gray-500 italic">Kosár betöltése...</div>

    <div v-else-if="cart.items.length === 0" class="text-center text-gray-400 italic">
      A kosár üres.
    </div>

    <ul v-else class="divide-y divide-gray-200 mb-4">
      <li v-for="item in cart.items" :key="item.id" class="flex justify-between items-center py-3">
        <div>
          <p class="font-medium text-gray-900">{{ item.name }}</p>
          <p class="text-sm text-gray-600">{{ item.price }} Ft × {{ item.quantity }}</p>
        </div>
        <button 
          @click="cart.removeItem(item.id)" 
          class="text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded border border-red-500 hover:bg-red-100 transition"
        >
          Törlés
        </button>
      </li>
    </ul>

    <p class="text-lg font-semibold text-gray-900 mb-4">
      Összesen: <span class="text-indigo-600">{{ cart.total }} Ft</span>
    </p>

    <button
      @click="addSampleProduct"
      class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded transition"
    >
      Teszt termék hozzáadása
    </button>
  </div>
</template>

