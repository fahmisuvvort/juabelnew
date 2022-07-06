import { Api } from 'boot/axios'

export default {

  getAdminConfig({commit}) {
    commit('SET_LOADING', true)
    Api().get('adminConfig').then((response) => {
      if(response.status == 200){
        this.commit('SET_CONFIG', response.data.results)
      }
    })
    .finally(() => {
      commit('SET_LOADING', false)
    })
  },
  getShop: ({commit}) => {
    Api().get('shop').then(response => {
      if(response.status == 200) {
        commit('SET_SHOP', response.data.results.shop)
        commit('SET_CONFIG', response.data.results.config)
      }
    })
  },
  setShop: ({commit}) => {
    Api().post('shop').then(response => {
      if(response.status == 200) {
        commit('SET_SHOP', response.data.results)
      }
    })
  },
  getConfig: ({commit}) => {
    Api().get('config').then(response => {
      if(response.status == 200) {
        commit('SET_CONFIG', response.data.results)
      }
    })
  },
  getInitialData: ({commit}) => {
    commit('SET_LOADING', true)
    Api().get('getInitialData').then(response => {
      if(response.status == 200) {
        commit('SET_SHOP', response.data.results.shop),
        commit('SET_CONFIG', response.data.results.config)
        commit('block/SET_BLOCKS', response.data.results.blocks, { root: true }),
        commit('product/SET_INITIAL_PRODUCTS', response.data.results.products, { root: true })
        commit('product/SET_PRODUCT_PROMO', response.data.results.product_promo, { root: true })
        commit('category/SET_CATEGORIES', response.data.results.categories, { root: true })
        commit('slider/SET_SLIDERS', response.data.results.sliders, { root: true })
        commit('post/SET_INITIAL_POST', response.data.results.posts, { root: true })
        commit('SET_LOADING', false)
        commit('SET_SESSION_ID', response.data.results.sess_id)
      }
    }).catch(() => {
      commit('SET_LOADING', false)
    })
  }
}