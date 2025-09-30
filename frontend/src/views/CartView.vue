<script setup lang="ts">
import { onMounted } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import type { CartItem } from '@/stores/cartStore'
import { useRouter } from 'vue-router'

const cart = useCartStore()
const router = useRouter()

onMounted(() => {
  cart.fetchCart()
})

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
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
      <h2 class="text-3xl font-semibold mb-6 text-gray-800">Kosár</h2>

      <div v-if="cart.items.length === 0" class="text-center text-gray-400 italic py-10">
        A kosár üres.
      </div>

      <ul v-else class="space-y-6">
        <li
          v-for="item in cart.items"
          :key="item.id"
          class="flex items-center bg-white rounded-lg shadow-sm p-4"
        >
          <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-200 mr-4">
            <img
              v-if="item.image"
              :src="
                item.image.startsWith('http') ? item.image : 'http://127.0.0.1:8000' + item.image
              "
              alt="Product image"
              class="w-full h-full object-cover"
            />
            <div v-else class="text-gray-400 italic">Nincs kép</div>
          </div>

          <!-- Adatok + vezérlők -->
          <div class="flex-1 flex flex-col justify-between h-full">
            <div>
              <p class="font-medium text-gray-900">{{ item.name }}</p>
              <p class="text-sm text-gray-600 mt-1">{{ $formatPrice(item.price) }} Ft</p>
            </div>

            <div class="flex items-center mt-3 space-x-3">
              <button
                @click="decrement(item)"
                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded flex items-center justify-center transition"
                :disabled="item.quantity <= 1"
              >
                −
              </button>

              <span class="px-3 text-gray-800">{{ item.quantity }}</span>

              <button
                @click="increment(item)"
                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded flex items-center justify-center transition"
              >
                +
              </button>

              <button
                @click="cart.removeItem(item.id)"
                class="ml-auto p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition"
                aria-label="Törlés"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  width="20"
                  height="20"
                  fill="currentColor"
                >
                  <path
                    d="M3 6h18v2H3V6zm2 4h14v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V10zm3 3v6h2v-6H8zm4 0v6h2v-6h-2zm4 0v6h2v-6h-2zM9 4V2h6v2h5v2H4V4h5z"
                  />
                </svg>
              </button>
            </div>
          </div>
        </li>
      </ul>

      <div class="mt-8 flex items-center justify-between gap-4">
        <p class="text-xl font-semibold text-gray-900">
          Összesen: <span class="bold text-black-600">{{ $formatPrice(cart.total) }} Ft</span>
        </p>
        <button
          @click="cart.clearCartFromServer"
          class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
        >
          Kosár ürítése
        </button>
      </div>

      <button
        v-if="cart.items.length > 0"
        @click="router.push('/checkout')"
        class="btn-grad mt-4 w-full"
      >
        Megrendelés
      </button>
    </div>
  </div>
</template>
