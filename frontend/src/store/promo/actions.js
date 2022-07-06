import { Api } from 'boot/axios'

export function getPromos ({commit}) {
  Api().get('promo').then(response => {
    if(response.status == 200) {
      commit('SET_PROMOS',  response.data.results)
    }
  })
}
export function storePromo ({ dispatch }, payload) {
  Api().post('promo', payload).then(() => {
    dispatch('getPromos')
  })
}
export function updatePromo ({ dispatch }, payload) {
  Api().post('promo/' + payload.id, payload).then(() => {
    dispatch('getPromos')
  })
}
export function deletePromo ({ dispatch }, id) {
  Api().delete('promo/' + id).then(() => {
    dispatch('getPromos')
  })
}
export function getProductPromo ({ commit }, id) {
  Api().get('getProductPromo' + id).then((response) => {
    if(response.status == 200) {
      commit('SET_PRODUCT_PROMO', response.data.results)
    }
  })
}




