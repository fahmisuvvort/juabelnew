import Vue from 'vue'
import axios from 'axios'
import { Notify } from 'quasar'
import Cookies from 'js-cookie';

Vue.prototype.$axios = axios

var BaseApi = axios.create({
  baseURL: process.env.API
})

BaseApi.defaults.headers.common = { 'X-Requested-With': 'XMLHttpRequest' }


export default ({ app, router, store, Vue }) => {

  BaseApi.interceptors.request.use(config => {
  
    return config
  }, error => {
    // Do something with request error

    store.commit('SET_LOADING', false)
  
    Notify.create({
      type: 'negative',
      message: 'Network Error'
    })
    return Promise.reject(error)
  })
  
  // Add a response interceptor
  BaseApi.interceptors.response.use(response => {
    store.commit('SET_LOADING', false)
    return response
  
  }, error => {
    store.commit('SET_LOADING', false)
  
    // var errors = error
    var errors = []
    let showMessage = true
  
    if (error.response) {
  
        // Session Expired
        if (401 === error.response.status) {
          showMessage = false
            errors.push(error.response.data.message)
            store.dispatch('user/exit')
            Notify.create({
              type: 'negative',
              message: 'Sesi anda sudah habis, silahkan login'
            });
          }
  
        // Errors from backend
        if (error.response.status == 422) {
            // errors = error.response.data.message

            for(var errorKey in error.response.data.errors) {
                for(var i = 0; i < error.response.data.errors[errorKey].length; i++) {
                    errors.push(String(error.response.data.errors[errorKey][i]))
                }
            }
        }
  
        // Backend error
        if (500 === error.response.status) {
            // errors = error.response.data.message
            errors.push('Something went wrong, Please try again')
        }
        if (400 === error.response.status) {
            // errors = error.response.data.message
            errors.push('Jaringan sedang bermasalah, silahkan tunggu beberapa saat.')
        }
  
    } else {
      store.commit('SET_LOADING', false)
      errors.push('Jaringan sedang bermasalah, silahkan tunggu beberapa saat.')
    }
  
    if(showMessage && errors.length) {
      Notify.create({
        type: 'negative',
        message: String(errors[0]),
      })
    }
    
    // Do something with response error       
    return Promise.reject(error)
  })

}

var Api = () => {

  const token = Cookies.get('__token')

  if(token) {
    BaseApi.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }

  return BaseApi
}

export { Api }


