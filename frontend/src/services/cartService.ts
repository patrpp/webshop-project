import axios from 'axios'

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL
axios.defaults.withCredentials = true

export interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image: string
}

export interface CartResponse {
  items: CartItem[]
  total: number
}

export interface AddItemRequest {
  id: number
  name: string
  price: number
}

const API_URL = '/cart'

export const getCart = (): Promise<CartResponse> =>
  axios.get<CartResponse>(API_URL).then(res => res.data)

export const addItem = (item: AddItemRequest): Promise<CartResponse> =>
  axios.post<CartResponse>(API_URL, item).then(res => res.data)

export const updateItem = (id: number, quantity: number): Promise<CartResponse> =>
  axios.put<CartResponse>(`${API_URL}/${id}`, { quantity }).then(res => res.data)

export const removeItem = (id: number): Promise<CartResponse> =>
  axios.delete<CartResponse>(`${API_URL}/${id}`).then(res => res.data)

