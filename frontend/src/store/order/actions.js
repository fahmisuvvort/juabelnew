import { Api } from 'boot/axios'
export function getOrders ({commit}) {
  Api().get('orders').then(response => {
    if(response.status == 200) {
      commit('SET_ORDERS', response.data.results)
    }
  })
}
export function getPaginateOrder ({commit}, payload) {
  commit('SET_LOAD_MORE', true)
  Api().get(`orders?filter=${payload.filter}&skip=${payload.skip}`).then(response => {
    if(response.status == 200) {
      commit('SET_PAGINATE_ORDERS', response.data.results)
    }
  }).finally(() => {
    commit('SET_LOAD_MORE', false)
  })
}
export function searchOrder ({dispatch, commit}, search) {
  commit('SET_LOADING', true, { root: true})
  Api().get(`orders?search=${search}`).then(response => {
    if(response.status == 200) {
      commit('SET_ORDERS', response.data.results)
    }
  }).finally(() => {
    commit('SET_LOADING', false, { root: true})
  })
}
export function filterOrder ({ commit }, payload) {
  commit('SET_LOADING', true, { root: true})
  Api().get('orders?filter='+ payload).then(response => {
    if(response.status == 200) {
      commit('SET_ORDERS', response.data.results)
    }
  }).finally(() => {
    commit('SET_LOADING', false, { root: true})
  })
}

export function getCustomerOrders ({ commit }) {
  Api().get('getCustomerOrders').then(response => {
    if(response.status == 200) {
      commit('SET_CUSTOMER_ORDERS', response.data.results)
    }
  })
}
export function getPaginateCustomerOrder ({ commit }, payload) {
  commit('SET_LOAD_MORE_CUSTOMER', true)
  Api().get('getCustomerOrders?skip=' + payload).then(response => {
    if(response.status == 200) {
      commit('SET_PAGINATE_CUSTOMER_ORDERS', response.data.results)
    }
  }).finally(() => {
    commit('SET_LOAD_MORE_CUSTOMER', false)
  })
}
export function getOrderById ({}, order_ref) {
  return Api().get('orders/'+ order_ref)
}
export function getTransactionDetail ({}, ref) {
  return Api().get('transaction/detail?reference='+ref)
}
export function storeOrder ({}, payload) {
  return Api().post('orders', payload)
}
export function updateOrder ({dispatch}, payload) {
  Api().post('orders/'+ payload.id, payload).then(response => {
    if(response.status == 200) {
      dispatch('getOrders')
    }
  })
}
export function destroyOrder ({ dispatch }, id) {
  return Api().delete('orders/'+ id)
}
export function acceptPayment ({ dispatch }, id) {
  return Api().post('paymentAccepted/'+ id)
}
export function cancelOrder ({ dispatch }, id) {
  return Api().post('cancelOrder/'+ id)
}

export function inputResi ({ dispatch }, payload) {
  return Api().post('inputResi', payload)
}
export function updateStatusOrder ({dispatch, commit}, payload) {
 return Api().post('updateStatusOrder', payload)
}
