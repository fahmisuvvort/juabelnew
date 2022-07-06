import { Api } from 'boot/axios'

export function getBanks ({commit}) {
  Api().get('banks').then(response => {
    if(200 == response.status) {
      commit('SET_BANKS', response.data.results)
    }
  })
}
export function storeBank ({dispatch}, payload) {
  Api().post('banks', payload).then(response => {
    if(201 == response.status) {
      dispatch('getBanks')
    }
  })
}
export function updateBank ({dispatch}, payload) {
  Api().post('banks/' + payload.id, payload).then(response => {
    if(200 == response.status) {
      dispatch('getBanks')
    }
  })
}
export function destroyBank ({dispatch}, id) {
  Api().delete('banks/' + id).then(response => {
    if(200 == response.status) {
      dispatch('getBanks')
    }
  })
}


