<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-xl font-bold mb-4">Rendelési adatok:</h1>
    <form @submit.prevent="submitOrder" class="space-y-4">
      <div class="relative z-0 w-full group">
        <input
          v-model="order.name"
          type="text"
          id="name"
          name="name"
          class="input-field-floating peer"
          placeholder=" "
          required
        />
        <label for="name" class="floating-label">Név</label>
      </div>

      <div class="relative z-0 w-full group">
        <input
          v-model="order.email"
          type="email"
          id="email"
          name="email"
          class="input-field-floating peer"
          placeholder=" "
          required
        />
        <label for="email" class="floating-label">Email</label>
      </div>

      <div class="relative z-0 w-full group">
        <input
          v-model="order.phone"
          type="tel"
          id="phone"
          name="phone"
          class="input-field-floating peer"
          placeholder=" "
          required
        />
        <label for="phone" class="floating-label">Telefonszám</label>
      </div>

      <div class="relative z-0 w-full group">
        <div class="flex gap-4">
          <div class="basis-1/4">
            <div class="relative z-0 w-full group">
              <input
                v-model="order.zip"
                type="text"
                id="zip"
                pattern="[0-9]{4}"
                class="input-field-floating peer"
                placeholder=" "
                required
              />
              <label for="zip" class="floating-label">Irányítószám</label>
            </div>
          </div>
          <div class="basis-3/4">
            <div class="relative z-0 w-full group">
              <input
                v-model="order.city"
                type="text"
                id="city"
                class="input-field-floating peer"
                placeholder=" "
                required
              />
              <label for="city" class="floating-label">Város</label>
            </div>
          </div>
        </div>
      </div>
      <div class="relative z-0 w-full group">
        <input
          v-model="order.address"
          type="text"
          id="address"
          name="address"
          class="input-field-floating peer"
          placeholder=" "
          required
        />
        <label for="address" class="floating-label">Utca / házszám</label>
      </div>
      <div class="flex justify-center">
        <button
          type="submit"
          class="btn-grad flex items-center justify-center gap-2 px-6 py-2 min-w-[100px]"
          :disabled="loading"
        >
          <template v-if="loading">
            <svg
              class="animate-spin h-5 w-5 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
              ></path>
            </svg>
            <span>Betöltés...</span>
          </template>
          <template v-else> Megrendelés elküldése </template>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'
import { useToastStore } from '@/stores/toastStore'

const cart = useCartStore()
const toast = useToastStore()

const order = reactive({
  name: '',
  zip: '',
  city: '',
  address: '',
  phone: '',
  email: '',
})

const loading = ref(false)

async function submitOrder() {
  loading.value = true
  try {
    const payload = {
      ...order,
      items: cart.items,
    }

    const response = await axios.post('http://127.0.0.1:8000/api/order', payload)

    cart.clearCartFromServer()

    toast.show('Köszönjük rendelését!', 'success')
  } catch (error) {
    console.error('Hiba történt a rendelés során:', error)

    toast.show('Hiba történt a rendelés során!', 'error')
  } finally {
    loading.value = false
  }
}
</script>
