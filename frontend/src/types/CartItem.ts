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
  image: string
}
