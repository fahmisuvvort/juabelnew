import { Api } from 'boot/axios'
import { Notify } from 'quasar'

export function getCategories({commit}) {
  Api().get('category').then(response => {
    if(response.status == 200) {
      commit('SET_CATEGORIES', response.data.results);
    }
  })
}
export function getAllCategories({commit}) {
  Api().get('category-all').then(response => {
    if(response.status == 200) {
      commit('SET_ALL_CATEGORIES', response.data.results);
    }
  })
}
export function getCategory ({}, payload) {
  return Api().get('category/' + payload)
}
export function categoryStore({dispatch, commit}, payload) {
  return Api().post('categories', payload, {headers: {'content-Type': 'multipart/formData'}})
}
export function categoryUpdate({dispatch, commit}, payload) {
  // console.log(Object.fromEntries(payload));
  return Api().post('categories/' + payload.id , payload.data, {headers: {'content-Type': 'multipart/formData'}})
}
export function categoryDelete ({commit, dispatch}, payload) {
  return Api().delete('categories/' + payload)
}