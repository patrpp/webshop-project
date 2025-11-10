// src/services/productService.ts
import axios from 'axios'

export interface Product {
  id: number
  name: string
  price: number
  net_price: number
  category: string
  category_id: string
  image_url: string
  description: string
}

interface ProductFilters {
  q?: string
  diameter?: number
}

export async function fetchProducts(filters: ProductFilters): Promise<Product[]> {
  try {
    const response = await axios.get('/products/filter', {
      params: {
        q: filters.q,
        diameter: filters.diameter,
      },
    })
    return response.data
  } catch (error) {
    console.error('Hiba a termékek lekérésekor:', error)
    return []
  }
}
