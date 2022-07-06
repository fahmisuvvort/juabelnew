import { Api } from 'boot/axios'

export function getPromotes ({commit}) {
  Api().get('promote').then(response => {
    if(response.status == 200) {
      commit('SET_PROMOTES',  response.data.results)
    }
  })
}
export function storePromote ({ dispatch }, payload) {
  Api().post('promote', payload).then(() => {
    dispatch('getPromotes')
  })
}
export function updatePromote ({ dispatch }, payload) {
  Api().post('promote/' + payload.id, payload).then(() => {
    dispatch('getPromotes')
  })
}
export function deletePromote ({ dispatch }, id) {
  Api().delete('promote/' + id).then(() => {
    dispatch('getPromotes')
  })
}
export function getProducts ({ commit }, id) {
  Api().get('getProductPromo' + id).then((response) => {
    if(response.statu == 200) {
      commit('SET_PRODUCT_PROMO', response.data.results)
    }
  })
}




