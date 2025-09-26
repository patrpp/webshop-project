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
      console.log('cart.init() hívva')
      await this.fetchCart()
      if (this.items.length === 0) {
        this.loadFromLocalStorage()
      }
      console.log('init vége, items:', this.items, 'total:', this.total)
    },

    async fetchCart() {
      console.log('fetchCart hívva')
      this.loading = true
      try {
        const response = await axios.get('/api/cart', { withCredentials: true })
        console.log('fetchCart válasz:', response.data)
        this.items = response.data.items
        this.total = response.data.total
      } catch (error) {
        console.error('Hiba a kosár lekérésekor:', error)
      } finally {
        this.loading = false
      }
    },

    async addToCart(product: { id: number; name: string; price: number ; image: string}) {
      console.log('addToCart: ', product)
      try {
        const response = await axios.post('/api/cart', product, { withCredentials: true })
        console.log('addToCart válasz:', response.data)
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch (error) {
        console.error('Hiba a kosárhoz adáskor:', error)
      }
    },

    async updateItemQuantity(id: number, quantity: number) {
      console.log('updateItemQuantity:', id, quantity)
      try {
        const response = await axios.put(`/api/cart/${id}`, { quantity }, { withCredentials: true })
        console.log('update válasz:', response.data)
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch (error) {
        console.error('Hiba a mennyiség frissítésekor:', error)
      }
    },

    async removeItem(id: number) {
      console.log('removeItem:', id)
      try {
        const response = await axios.delete(`/api/cart/${id}`, { withCredentials: true })
        console.log('remove válasz:', response.data)
        this.items = response.data.items
        this.total = response.data.total
        this.saveToLocalStorage()
      } catch (error) {
        console.error('Hiba a kosárból törléskor:', error)
      }
    },

    clearCart() {
      console.log('clearCart hívva')
      this.items = []
      this.total = 0
      localStorage.removeItem('cart')
    },

    saveToLocalStorage() {
      console.log('saveToLocalStorage, items:', this.items, 'total:', this.total)
      localStorage.setItem('cart', JSON.stringify({
        items: this.items,
        total: this.total
      }))
    },

    loadFromLocalStorage() {
      console.log('loadFromLocalStorage hívva')
      const cartData = localStorage.getItem('cart')
      if (cartData) {
        try {
          const parsed = JSON.parse(cartData)
          console.log('localStorage parse:', parsed)
          this.items = parsed.items || []
          this.total = parsed.total || 0
        } catch (e) {
          console.error('Hiba a localStorage betöltésekor:', e)
        }
      }
    }
  }
})

