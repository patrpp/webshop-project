import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ProductView from '@/views/ProductView.vue'
import ProductList from '@/components/ProductList.vue'
import Cart from '@/views/CartView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/cart',
      name: 'CartView',
      component: () => import('@/views/CartView.vue')
    },
 {
  path: '/products/:id',
  name: 'product',
  component: () => import('@/views/ProductView.vue')
},
{
  path: '/products',
  name: 'ProductList',
  component: () => import('@/components/ProductList.vue'),
},
  ],
})

export default router
