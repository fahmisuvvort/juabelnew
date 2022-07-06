import { Api } from 'boot/axios'
import Cookies from 'js-cookie';

export function login ({commit, dispatch}, payload) {
  let self = this
  commit('SET_LOADING', true, { root: true })

  Api().post('user/login', payload)
  .then(response => {
    if (response.status == 200) {
      let user = response.data.results
      commit('SET_USER', user)
      Cookies.set('__token', response.data.token, { expires: 1 })
      if(user.role == 'admin') {
        self.$router.push({name: 'Settings'})
      } else if(user.role == 'seller') {
        self.$router.push({name: 'SellerHome'})
      } else {
        self.$router.push({name: 'CustomerOrder'})
      }
    }
  })
  .finally(() => {
    commit('SET_LOADING', false, { root: true })
  })

}
export function register ({commit, dispatch}, payload) {
  let self = this
  commit('SET_LOADING', true, { root: true })

  Api().post('user/register', payload)
  .then(response => {
    if (response.status == 201) {
      Cookies.set('__token', response.data.token, { expires: 1 })
      let user = response.data.results
      commit('SET_USER', user)
      if(user.role == 'admin') {
        self.$router.push({name: 'Settings'})
      } else {
        self.$router.push({name: 'CustomerOrder'})
      }
    }
  })
  .finally(() => {
    commit('SET_LOADING', false, { root: true })
  })

}
export function logout({ commit }) {
  commit('LOGOUT')
  Cookies.remove('__token')
  this.$router.push('/')
  Api().post('user/logout')
}
export function exit({ commit }) {
  commit('LOGOUT')
  Cookies.remove('__token')
  this.$router.push('/')
}
export function getUser ({ commit }) {
  let self = this
  Api().get('user').then(response => {
    if(response.status == 200) {
      commit('SET_USER', response.data.results)
    }
  }).catch((error) => {
    if(401 === error.response.status) {
        commit('LOGOUT')
        self.$router.push('/')
    }
  })
}
export function updateUser ({commit}, payload) {
  let self = this
  Api().post('user/update', payload).then(response => {
    if(response.status == 200) {
      commit('SET_USER', response.data.results)
      commit('SET_LOADING', false, {root: true})
      self.$router.push({name: 'Settings'})
    }
  }).catch((error) => {
      commit('SET_LOADING', false, {root: true})
      if(401 === error.response.status) {
          commit('LOGOUT')
          self.$router.push('/')
      }
  })
}

export function requestPasswordToken({commit}, payload) {
  return Api().post('requestPasswordToken', payload)
}

export function resetPassword({}, payload) {
  let self = this
  Api().post('resetPassword', payload).then(response => {
    if(response.status == 200) {
      localStorage.removeItem('is_reqpwd')
      self.$router.push({name: 'Login'})
      Notify.create({
        type: 'positive',
        message: 'Berhasil merubah kata sandi, Silahkan login dengan sandi baru.'
      })

      localStorage.removeItem('is_reqpwd')

    }
  })
}
