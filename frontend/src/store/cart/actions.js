import { Api } from 'boot/axios'

export function getCarts ({ commit }, id) {
  Api().get('carts/' + id).then(response => {
    if(response.status == 200) {
      commit('SET_CARTS', response.data.results)
      commit('COMMIT_CARTS')
    }
  })

}
export function addToCart ({ commit }, payload) {

  commit('ADD_TO_CART', payload)
  
  Api().post('carts/' + payload.session_id, payload)
  .then(response => {
    if(response.status == 200) {
      commit('COMMIT_CARTS')
    }
  })
  .catch(() => {
    commit('ROLLBACK_CARTS')
  })

}
export function updateCart ({ commit }, payload) {

  commit('UPDATE_CART', payload)

  Api().post('carts/' + payload.session_id, {...payload, _method: 'PUT'})
  .then(response => {
    if(response.status == 200) {
      commit('COMMIT_CARTS')
    }
  })
  .catch(() => {
    commit('ROLLBACK_CARTS')
  })

}

export function removeCart ({ commit }, payload) {

  commit('REMOVE_CART', payload)
  Api().post('cart/delete', payload)
  .then(response => {
    if(response.status == 200) {
      commit('COMMIT_CARTS')
    }
  })
  .catch(() => {
    commit('ROLLBACK_CARTS')
  })

}

export function clearCart({commit}, sessId) {
  commit('CLEAR_CART')
  Api().post('clearCart', { session_id: sessId})
  .then(response => {
    if(response.status == 200) {
      commit('COMMIT_CARTS')
    }
  })
  .catch(() => {
    commit('ROLLBACK_CARTS')
  })
}
