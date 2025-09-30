import axios from 'axios'

axios.defaults.baseURL = 'http://127.0.0.1:8000'
axios.defaults.withCredentials = true

import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)

app.use(Toast, {
  transition: "Vue-Toastification__bounce",
  maxToasts: 20,
  newestOnTop: true
})

app.config.globalProperties.$formatPrice = (value: number) => {
  return new Intl.NumberFormat('hu-HU').format(value)
}
const pinia = createPinia()
app.use(pinia)       
app.use(router)

app.mount('#app')

