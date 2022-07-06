import { AddressbarColor } from 'quasar'

export default {
  SET_ERRORS: (state,payload) => {
    state.errors = payload
  },
  SET_LOADING: (state, payload) => {
    state.loading = payload
  },
  SET_SHOP: (state, shop) => {
    state.shop = shop
    // localStorage.setItem('_washop', JSON.stringify(shop))
  },
  SET_BLOCK: (state, payload) => {
    state.blocks = payload
  },
  SET_META_TITLE: (state, payload) => {
    state.meta.title = payload
  },
  SET_CONFIG: (state, payload) => {

    state.config = payload
    // localStorage.setItem('shop_config', JSON.stringify(payload))
    document.body.style.setProperty('--q-color-primary', payload.theme_color)

    AddressbarColor.set(state.theme_color)

  },
  SET_HOME_VIEW_MODE: (state, payload) => {
    state.config.home_view_mode = payload
    // localStorage.setItem('shop_config', JSON.stringify(state.config))
  },
  SET_PRODUCT_VIEW_MODE: (state, payload) => {
    state.config.product_view_mode = payload
    // localStorage.setItem('shop_config', JSON.stringify(state.config))
  },
  SET_THEME: (state, payload) => {
    state.config.theme = payload
    // localStorage.setItem('shop_config', JSON.stringify(state.config))
  },
  SET_DEMO: (state, payload) => {
    state.isDemoMode = payload
  },
  SET_THEME_COLOR: (state, clr) => {
    state.config.theme_color = clr
    document.body.style.setProperty('--q-color-primary', clr)
    // localStorage.setItem('shop_config', JSON.stringify(state.config))
    AddressbarColor.set(state.theme_color)
  },
  SET_INSTALL_APP: (state, payload) => {
    state.deferredPrompt = payload
  },
  REMOVE_INSTALL_APP: (state) => {
    state.deferredPrompt = null
  },

  SET_GUEST_CHECKOUT: (state, status) => {
    state.config.is_guest_checkout = status
  },
  SET_WHATSAPP_CHECKOUT: (state, status) => {
    state.config.is_whatsapp_checkout = status
  },
  SET_SESSION_ID: ( state, payload) => {
    if(!state.session_id) {
      state.session_id = payload
    }
  },
  SET_MENU_CATEGORY: (state, status) => {
    state.isMenuCategory = status
  }

}