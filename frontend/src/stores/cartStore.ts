import { defineStore } from 'pinia'
import axios from 'axios'

export interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image: string
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [] as CartItem[],
    total: 0,
    loading: false,
  }),

  actions: {
    async init() {
      await this.fetchCart()
      if (this.items.length === 0) {
        this.loadFromLocalStorage()
      }
    },

    async fetchCart() {
      this.loading = true
      try {
        const response = await axios.get('/api/cart', { withCredentials: true })
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage() 
      } catch (error) {
        console.error('Hiba a kosár lekérésekor:', error)
      } finally {
        this.loading = false
      }
    },

    async addToCart(productId: number) {
      try {
        const response = await axios.post(
          '/api/cart',
          { id: productId },
          { withCredentials: true }
        )
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch {
      }
    },

    async updateItemQuantity(id: number, quantity: number) {
      try {
        const response = await axios.put(
          `/api/cart/${id}`,
          { quantity },
          { withCredentials: true }
        )
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch {
      }
    },

    async removeItem(id: number) {
      try {
        const response = await axios.delete(`/api/cart/${id}`, { withCredentials: true })
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch {
      }
    },

    async clearCartFromServer() {
      try {
        const response = await axios.post('/api/cart/clear', {}, { withCredentials: true })
        this.items = response.data.items
        this.total = response.data.total
        this.clearCart()
      } catch {
      }
    },

    clearCart() {
      this.items = []
      this.total = 0
      localStorage.removeItem('cart')
    },

    saveToLocalStorage() {
      localStorage.setItem(
        'cart',
        JSON.stringify({
          items: this.items,
          total: this.total,
        })
      )
    },

    loadFromLocalStorage() {
      const cartData = localStorage.getItem('cart')
      if (cartData) {
        try {
          const parsed = JSON.parse(cartData)
          this.items = parsed.items || []
          this.total = parsed.total || 0
        } catch {
        }
      }
    },
  },
})