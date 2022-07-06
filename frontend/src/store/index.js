import Vue from 'vue'
import Vuex from 'vuex'

import createPersistedState from "vuex-persistedstate";
import Cookies from 'js-cookie';

import SecureLS from "secure-ls";
var ls = new SecureLS({ isCompression: false });

import user from './user'
import product from './product'
import category from './categories'
import slider from './slider'
import post from './post'
import block from './block'
import order from './order'
import bank from './bank'
import cart from './cart'
import discount from './discount'
import coupon from './coupon'
import promo from './promo'
import seller from './seller'

import mutations from './mutations'
import getters from './getters'
import actions from './actions'

const stateData = createPersistedState({
  key: '__state',
  paths: ['user', 'cart', 'shop', 'config', 'session_id', 'product.favorites', 'coupon.coupon_discount'],
  storage: {
    getItem: (key) => ls.get(key),
    setItem: (key, value) => ls.set(key, value, { expires: 1 }),
    removeItem: (key) => ls.remove(key),
  }
})

Vue.use(Vuex)

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default function (/* { ssrContext } */) {

  const Store = new Vuex.Store({
    state: {
      themes: ['default', 'elegant', 'romance'],
      errors: {},
      loading: false,
      shop: null,
      config: null,
      deferredPrompt: null,
      isMenuCategory: false,
      session_id: null,
      meta: {
        title: '',
        description: 'Simple Web Commerce checkout whatsapp'
      }
    },
    actions,
    mutations,
    getters,
    modules: {
      user,
      product,
      category,
      slider,
      post,
      block,
      order,
      bank,
      cart,
      discount,
      coupon,
      promo,
      seller,
    },
    plugins: [stateData],

    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEBUGGING
  })

  return Store
}
