<template>
  <div class="max-w-md mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Rendelési adatok:</h1>
    <form @submit.prevent="submitOrder">
      <div class="mb-2">
        <label for="name">Név:</label>
        <input v-model="order.name" id="name" required class="form-input" />
      </div>
      <div class="mb-2">
        <label for="zip">Irányítószám:</label>
        <input v-model="order.zip" id="zip" required class="form-input" />
      </div>
      <div class="mb-2">
        <label for="city">Város:</label>
        <input v-model="order.city" id="city" required class="form-input" />
      </div>
      <div class="mb-2">
        <label for="address">Utca / házszám:</label>
        <input v-model="order.address" id="address" required class="form-input" />
      </div>
      <div class="mb-2">
        <label for="phone">Telefonszám:</label>
        <input v-model="order.phone" id="phone" required class="form-input" />
      </div>
      <div class="mb-2">
        <label for="email">E-mail:</label>
        <input v-model="order.email" id="email" type="email" required class="form-input" />
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Megrendelés elküldése</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import axios from 'axios'
import { useCartStore } from '@/stores/cartStore'

const cart = useCartStore()

const order = reactive({
  name: '',
  zip: '',
  city: '',
  address: '',
  phone: '',
  email: ''
})

async function submitOrder() {
  try {
    // Feltételezve, hogy a cartStore-ban vannak a kosár tételek
    const payload = {
      ...order,
      items: cart.items // vagy ahogy a kosarad tárolja a tételeket
    }

    const response = await axios.post('http://127.0.0.1:8000/api/order', payload)
    console.log('Rendelés sikeres:', response.data)
    alert('Sikeres rendelés!')

    cart.clear()
  } catch (error) {
    console.error('Hiba történt a rendelés során:', error)
    alert('Hiba történt a rendelés során.')
  }
}
</script>
