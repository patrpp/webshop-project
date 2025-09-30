import { defineStore } from 'pinia'
import { ref } from 'vue'

interface ToastItem {
  id: number
  message: string
  type: 'success' | 'error'
  duration: number
}

export const useToastStore = defineStore('toast', () => {
  const toasts = ref<ToastItem[]>([])
  let nextId = 1

  function show(
    message: string,
    type: 'success' | 'error' = 'success',
    duration = 3000
  ) {
    const id = nextId++
    toasts.value.push({ id, message, type, duration })

    // Automatikus eltűnés
    setTimeout(() => {
      remove(id)
    }, duration)
  }

  function remove(id: number) {
    toasts.value = toasts.value.filter(toast => toast.id !== id)
  }

  return { toasts, show, remove }
})
