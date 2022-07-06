import { Api } from 'boot/axios'

export function getsellers ({commit}) {
  Api().get('sellers').then(response => {
    if(200 == response.status) {
      commit('SET_SELLERS', response.data.results)
    }
  })
}
export function storeSeller ({dispatch}, payload) {
  return Api().post('sellers', payload)
}
export function updateSeller ({dispatch}, payload) {
  return Api().post('sellers/' + payload.id, payload)
}
export function destroySeller ({dispatch}, id) {
  return Api().delete('sellers/' + id)
}
export function findSelelr ({dispatch}, id) {
  return Api().delete('sellers/' + id)
}
export function getProducts ({commit}, payload) {
  let url = 'seller/products'
  if(payload && payload.id) {
    url += `/${payload.id}`
  }
  if(payload && payload.skip) {
    url += `?skip=${payload.skip}`
  }
   Api().get(url).then(response =>  {
    commit('SET_SELLER_PRODUCTS', response.data)
   })
}
export function getPaginateProduct ({commit}, payload) {
  let url = 'seller/products?skip=' + payload.skip

  if(payload.filter) {
    url += `?filter=${payload.filter}`
  }
   Api().get(url).then(response =>  {
    commit('PAGINATE_SELLER_PRODUCTS', response.data)
   })
}
export function searchProductVendor ({commit}, key) {
   return Api().get('seller/searchProducts/' + key)
}

// seller order
export function getOrder ({commit}, id) {
  let url = 'seller/orders'
  if(id) {
    url += '/' + id
  }
   Api().get(url).then(response =>  {
    commit('SET_SELLER_ORDERS', response.data)
   })
}
export function filterOrder ({ commit }, filter) {
   Api().get('seller/orders?filter=' + filter).then(response =>  {
    commit('SET_SELLER_ORDERS', response.data)
   })
}
export function paginateOrder ({ commit }, payload) {
  let url = 'seller/orders?skip=' + payload.skip

  if(payload.filter) {
    url += `?filter=${payload.filter}`
  }
   Api().get(url).then(response =>  {
    commit('PAGINATE_SELLER_ORDERS', response.data)
   })
}


