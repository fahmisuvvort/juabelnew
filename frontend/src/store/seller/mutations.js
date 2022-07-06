
export function SET_SELLERS (state, payload) {
  state.sellers = payload
}

export function SET_SELLER_PRODUCTS (state, payload) 
{
  state.products.data = payload.results
  state.products.ready = true
  state.products.count = payload.count
  state.products.limit = payload.limit

  state.products.available = payload.count > 0 ? true : false
}
export function PAGINATE_SELLER_PRODUCTS (state, payload) 
{
  state.products.data = [... state.products.data, ...payload.results ]
  state.products.ready = true
  state.products.count = payload.count
  state.products.limit = payload.limit

  state.products.available = payload.count > 0 ? true : false
}
export function SET_SELLER_ORDERS (state, payload) 
{
  state.orders.data = payload.results
  state.orders.ready = true
  state.orders.count = payload.count
  state.orders.limit = payload.limit

  state.orders.available = payload.count > 0 ? true : false
}
export function PAGINATE_SELLER_ORDERS (state, payload) 
{
  state.orders.data = [...state.orders.data, ...payload.results ]
  state.orders.ready = true
  state.orders.count = payload.count
  state.orders.limit = payload.limit

  state.orders.available = payload.count > 0 ? true : false
}