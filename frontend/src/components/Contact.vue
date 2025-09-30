<template>
  <transition name="fade">
    <div
      v-if="showToast"
      class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded shadow-md z-50"
    >
      Üzeneted sikeresen elküldve!
    </div>
  </transition>

  <transition name="fade">
    <div
      v-if="showErrorToast"
      class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-2 rounded shadow-md z-50"
    >
      Hiba történt, próbáld újra!
    </div>
  </transition>

  <div class="min-h-screen flex flex-col items-center justify-center px-4">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-6 text-center">Kapcsolat</h1>
      <p class="text-gray-600 text-center mb-6">Írj nekünk üzenetet!</p>

      <form @submit.prevent="submitForm" class="space-y-4">
        <div class="relative z-0 w-full group">
          <input
            v-model="contact.name"
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
            v-model="contact.email"
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
          <textarea
            v-model="contact.message"
            id="message"
            name="message"
            rows="5"
            class="input-field-floating peer resize-none"
            placeholder=" "
            required
          ></textarea>
          <label for="message" class="floating-label">Üzenet</label>
        </div>

        <div class="flex justify-center">
          <button
            type="submit"
            class="btn-grad flex items-center justify-center gap-2 px-6 py-2"
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
              <span>Küldés...</span>
            </template>
            <template v-else> Küldés </template>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const contact = reactive({
  name: '',
  email: '',
  message: '',
})

const loading = ref(false)
const showToast = ref(false)
const showErrorToast = ref(false)

async function submitForm() {
  loading.value = true
  try {
    await axios.post('http://127.0.0.1:8000/api/contact', { ...contact })

    showToast.value = true

    // Mezők kiürítése
    contact.name = ''
    contact.email = ''
    contact.message = ''

    setTimeout(() => {
      showToast.value = false
    }, 3000)
  } catch (error) {
    console.error('Hiba az üzenet küldésekor:', error)
    showErrorToast.value = true
    setTimeout(() => {
      showErrorToast.value = false
    }, 3000)
  } finally {
    loading.value = false
  }
}
</script>
