import { Api } from 'boot/axios'

export function getCoupons ({commit}) {
  Api().get('coupon').then(response => {
    if(response.status == 200) {
      commit('SET_COUPONS',  response.data.results)
    }
  })
}
export function storeCoupon ({ dispatch }, payload) {
  Api().post('coupon', payload).then(() => {
    dispatch('getCoupons')
  })
}
export function updateCoupon ({ dispatch }, payload) {
  Api().post('coupon/' + payload.discount_id, payload).then(() => {
    dispatch('getCoupons')
  })
}
export function deleteCoupon ({ dispatch }, id) {
  Api().delete('coupon/' + id).then(() => {
    dispatch('getCoupons')
  })
}

export function redeemCoupon ({}, payload) {
  return Api().post('redeemCoupon', payload)
}