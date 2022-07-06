import { Api } from 'boot/axios'

export function getDiscounts ({commit}) {
  Api().get('discount').then(response => {
    if(response.status == 200) {
      commit('SET_DISCOUNTS',  response.data.results)
    }
  })
}
export function storeDiscount ({ dispatch }, payload) {
  Api().post('discount', payload).then(() => {
    dispatch('getDiscounts')
  })
}
export function updateDiscount ({ dispatch }, payload) {
  Api().post('discount/' + payload.id, payload).then(() => {
    dispatch('getDiscounts')
  })
}
export function deleteDiscount ({ dispatch }, id) {
  Api().delete('discount/' + id).then(() => {
    dispatch('getDiscounts')
  })
}

