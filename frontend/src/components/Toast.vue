<template>
  <div class="toast-container">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      :class="['toast', toast.type === 'error' ? 'toast-error' : 'toast-success']"
      @mouseenter="pauseTimeout(toast.id)"
      @mouseleave="resumeTimeout(toast.id)"
    >
      {{ toast.message }}
      <button class="close-btn" @click="remove(toast.id)">×</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useToastStore } from '@/stores/toastStore'

const toastStore = useToastStore()

const toasts = computed(() => toastStore.toasts)

const timeouts = new Map<number, number>()

function remove(id: number) {
  toastStore.remove(id)
  clearTimeout(timeouts.get(id))
  timeouts.delete(id)
}

function pauseTimeout(id: number) {
  clearTimeout(timeouts.get(id))
  timeouts.delete(id)
}

function resumeTimeout(id: number) {
  const toast = toastStore.toasts.find(t => t.id === id)
  if (toast) {
    timeouts.set(
      id,
      window.setTimeout(() => {
        remove(id)
      }, toast.duration)
    )
  }
}
</script>
