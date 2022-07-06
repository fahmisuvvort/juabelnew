<template>
  <q-page class="bg-grey-1"
  :class="{'flex flex-center' : !carts.items.length}"
  >
    <q-header class="text-primary bg-white box-shadow">
        <q-toolbar>
          <q-btn :to="{ name: 'ProductIndex'}"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">Keranjang Belanja</q-toolbar-title>
        </q-toolbar>
    </q-header>
    <div v-if="carts.items.length" class="q-py-sm">
      <q-list class="bg-white" separator padding>
        <q-item v-for="cart in carts.items" :key="cart.sku" class="q-pa-sm">
          <q-item-section side class="q-pr-sm">
           <q-img :src="cart.image_url" style="width:90px;height:90px;"></q-img>
          </q-item-section>
          <q-item-section>
            <div class="col overflow-hidden full-width">
              <div class="text-weight-medium ellipsis">{{ cart.name }}</div>
              <div class="text-grey-7 q-mb-xs text-caption" v-if="cart.note">{{ cart.note }}</div>
              <div>Harga {{  moneyIDR(cart.price) }}</div>
              <div class="flex items-center justify-between q-mt-sm">
                <div class="q-gutter-x-sm items-center">
                  <q-btn flat padding="3px" round icon="remove_circle_outline" size="13px" @click="decrementQty(cart)" style="cursor:pointer;"></q-btn>
                  <span class="text-weight-medium text-md">{{ cart.quantity }}</span>
                  <q-btn flat padding="3px" round icon="add_circle_outline" size="13px" @click="incrementQty(cart)" style="cursor:pointer;"></q-btn>
                </div>
                <q-btn @click="removeCart(cart)" outline size="10px" color="red" no-caps padding="2px 4px">hapus</q-btn>
              </div>
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    <!-- <q-card flat square class="">
      <q-card-section>
        <div class="flex justify-end q-py-md q-px-sm bg-grey-1">
          <div class="column items-end">
            <div class="cart-coupon q-mt-lg">
              <div class="text-medium q-mb-md text-grey-7">Punya Code voucher?</div>
              <q-form @submit.prevent="handleRedeemCoupon">
                <div class="flex items-center flex-no-wrap q-gutter-x-sm">
                  <q-input padding="10px" required v-model="couponCode" borderless class="q-px-md bg-grey-3 col rounded15" placeholder="Masukan Kode..."></q-input>
                  <q-btn type="submit" no-caps outline color="teal" class="rounded15 text-weight-bold" unelevated style="padding:8px;">
                    <span>Gunakan</span>
                  </q-btn>
                </div>
              </q-form>
            </div>
          </div>
        </div>
    </q-card-section>
  </q-card> -->
    </div>
    <div v-if="!carts.items.length" class="column items-center">
      <p class="text-grey-8 text-weight-bold text-center">Keranjang belanja anda masih kosong!</p>
      <q-btn unelevated :to="{name: 'ProductIndex'}" rounded text-color="white" color="primary"
        icon="keyboard_backspace" label="kembali berbelanja" no-caps/>
    </div>
    <q-dialog v-model="directCheckoutModal" persistent>
      <div style="width:100%;max-width:430px;">
        <direct-checkout-block @close="directCheckoutModal = false" />
      </div>
    </q-dialog>
    <q-dialog 
      v-model="loginModal" 
      persistent
      transition-show="slide-up"
      transition-hide="slide-down"
     >
      <login-block @onResponse="onResponse" @onClose="loginModal = false"/>
    </q-dialog>
    <q-footer v-if="carts.items.length" class="bg-white q-pa-md">
      <div class="q-pb-sm flex justify-between">
        <div class="text-md text-dark">Subtotal</div>
        <div class="text-md2 text-weight-bold text-secondary">{{ moneyIDR(carts.subtotal) }}</div>
      </div>
      <q-btn v-if="isCanChekout" unelevated @click="checkout" color="primary" class="full-width" no-caps>
        <svg
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:cc="http://creativecommons.org/ns#"
        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
        xmlns:svg="http://www.w3.org/2000/svg"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        height="20"
        width="20"
        id="svg2"
        version="1.1">
        <defs
          id="defs6" />
        <path
          id="path817"
          d="M 6.829268,18.2927 C 6.206549,17.66998 6.208549,16.962199 6.834768,16.335948 7.295075,15.87564 7.299158,15.833489 6.895748,15.70635 4.7751346,15.038014 4.5526598,14.587213 4.3890898,10.627041 4.2795602,7.9752375 4.1370612,7.0800955 3.5418488,5.3048955 L 2.8262839,3.1707495 H 1.7964173 C 0.8348259,3.1707495 0,2.7173665 0,2.1951395 c 0,-0.557993 0.8368698,-0.97561 1.9550402,-0.97561 1.8447535,0 2.2088159,0.47497 3.7390088,4.878049 l 1.313822,3.780488 h 4.072009 4.072008 l 1.485653,-3.353659 1.485657,-3.353658 h 0.9384 c 0.516122,0 0.938402,0.04665 0.938402,0.103676 0,0.355734 -3.738563,8.3325965 -3.976041,8.4835585 -0.160739,0.102181 -2.405057,0.187463 -4.987374,0.189518 l -4.695122,0.0037 v 0.827563 c 0,0.529633 0.138893,0.880862 0.385803,0.97561 0.21219,0.08143 2.517068,0.148046 5.121951,0.148046 h 4.736149 v 0.97561 c 0,0.813008 -0.0813,0.97561 -0.487805,0.97561 h -0.487805 l 0.487805,0.487805 c 0.268293,0.268292 0.487805,0.707317 0.487805,0.975609 0,0.268293 -0.219512,0.707317 -0.487805,0.97561 -0.268293,0.268293 -0.707317,0.487805 -0.97561,0.487805 -0.628185,0 -1.463414,-0.835228 -1.463414,-1.463415 0,-0.268292 0.219512,-0.707317 0.487804,-0.975609 l 0.487805,-0.487805 H 11.463415 8.292683 l 0.487805,0.487805 c 0.268292,0.268292 0.487805,0.707317 0.487805,0.975609 0,0.628187 -0.83523,1.463415 -1.463415,1.463415 -0.268293,0 -0.707317,-0.219512 -0.97561,-0.487805 z M 8.04878,17.31709 c 0,-0.134146 -0.109756,-0.243902 -0.243902,-0.243902 -0.134146,0 -0.243902,0.109756 -0.243902,0.243902 0,0.134146 0.109756,0.243903 0.243902,0.243903 0.134146,0 0.243902,-0.109757 0.243902,-0.243903 z m 7.317074,0 c 0,-0.134146 -0.109756,-0.243902 -0.243903,-0.243902 -0.134146,0 -0.243902,0.109756 -0.243902,0.243902 0,0.134146 0.109756,0.243903 0.243902,0.243903 0.134147,0 0.243903,-0.109757 0.243903,-0.243903 z M 11.707317,6.0339515 V 5.1219685 H 9.756098 7.804878 v -0.97561 -0.975609 h 1.95122 1.951219 v -0.911983 -0.911983 l 1.399788,1.399788 1.399788,1.399787 -1.399788,1.399788 -1.399788,1.399788 z"
          style="fill:currentColor;stroke-width:0.24390244" />
        </svg>
        <span class="q-ml-sm">
          Checkout Sekarang
        </span>
      </q-btn>
     <q-btn v-if="isCanCheckoutWhatsapp" unelevated @click="directCheckout" color="green-6" class="full-width q-mt-sm" no-caps>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" width="20px" height="20px">
          <g id="surface1441897">
          <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 25 2 C 12.316406 2 2 12.316406 2 25 C 2 28.960938 3.023438 32.855469 4.964844 36.289062 L 2.035156 46.730469 C 1.941406 47.074219 2.035156 47.441406 2.28125 47.695312 C 2.472656 47.894531 2.734375 48 3 48 C 3.078125 48 3.160156 47.988281 3.238281 47.972656 L 14.136719 45.273438 C 17.464844 47.058594 21.210938 48 25 48 C 37.683594 48 48 37.683594 48 25 C 48 12.316406 37.683594 2 25 2 Z M 36.570312 33.117188 C 36.078125 34.476562 33.71875 35.722656 32.585938 35.886719 C 31.566406 36.035156 30.277344 36.101562 28.863281 35.65625 C 28.007812 35.386719 26.90625 35.027344 25.496094 34.429688 C 19.574219 31.902344 15.707031 26.011719 15.410156 25.625 C 15.117188 25.234375 13 22.464844 13 19.59375 C 13 16.726562 14.523438 15.3125 15.066406 14.730469 C 15.609375 14.144531 16.246094 14 16.640625 14 C 17.035156 14 17.429688 14.003906 17.773438 14.019531 C 18.136719 14.039062 18.625 13.882812 19.101562 15.023438 C 19.59375 16.191406 20.777344 19.058594 20.921875 19.351562 C 21.070312 19.644531 21.167969 19.984375 20.972656 20.375 C 20.777344 20.761719 20.679688 21.007812 20.382812 21.347656 C 20.085938 21.6875 19.761719 22.105469 19.496094 22.367188 C 19.199219 22.660156 18.894531 22.976562 19.238281 23.558594 C 19.582031 24.144531 20.765625 26.050781 22.523438 27.597656 C 24.777344 29.585938 26.679688 30.199219 27.269531 30.492188 C 27.859375 30.785156 28.203125 30.734375 28.550781 30.347656 C 28.894531 29.957031 30.023438 28.644531 30.417969 28.058594 C 30.8125 27.476562 31.203125 27.574219 31.746094 27.769531 C 32.289062 27.960938 35.191406 29.371094 35.78125 29.664062 C 36.371094 29.957031 36.765625 30.101562 36.914062 30.34375 C 37.0625 30.585938 37.0625 31.753906 36.570312 33.117188 Z M 36.570312 33.117188 "/>
          </g>
        </svg>
        <span class="q-ml-sm">
          Order Via Whatsapp
        </span>
      </q-btn>
    </q-footer>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import DirectCheckoutBlock from './components/DirectCheckoutBlock.vue'
import LoginBlock from 'components/LoginBlock'
export default {
  name: 'CartPage',
  components: { DirectCheckoutBlock, LoginBlock },
  data () {
    return {
      loginModal: false,
      checkoutDemoModal: false,
      directCheckoutModal: false,
      discount: 0,
      currentOrder: null,
      couponCode: '',
      isCoupon: false
    }
  },
  computed: {
    ...mapState({
      shop: state => state.shop,
      config: state => state.config,
      user: state => state.user.user,
      coupon_discount: state => state.cart.coupon_discount
    }),
    showCouponForm() {
      return this.isCoupon || this.coupon_discount ? true : false
    },
    carts() {
      return this.$store.getters['cart/getCarts']
    },
    session_id() {
      return this.$store.state.session_id
    },
    isCanChekout: function() {
      if(this.carts.items.length && this.config && this.config.can_shipping) {
        if(this.config.is_tripay_ready || this.config.is_bank_ready) {
          return true
        } else {
          return false
        }
      } else {
        return false
      }
    },
    isCanCheckoutDirectWithShipping() {
      return this.carts.items.length && this.shop.phone && this.config && this.config.can_shipping ? true : false
    },
    isCanCheckoutWhatsapp() {
      return this.config && this.config.is_whatsapp_checkout && this.shop.phone ? true : false
    }
  },
  created() {
    if(!this.shop) {
      this.$store.dispatch('getShop')
    }
  },
  mounted() {
    if(this.coupon_discount) {
      this.couponCode = this.coupon_discount.code
    }
  },
  methods: {
    ...mapActions('coupon', ['redeemCoupon']),
    handleRedeemCoupon() {
      this.redeemCoupon({ code: this.couponCode }).then(response => {
        if(response.status == 200) {
          this.$store.commit('cart/SET_COUPON_DISCOUNT', response.data.results)
        }
      }).catch(err => {
        if(err && err.response && err.response.status == 404) {
          this.$q.notify({
            type: 'negative',
            message: err.response.data.message
          })
          this.couponCode = ''
        }
      })
    },
    removeCoupon() {
      this.couponCode = ''
      this.$store.commit('cart/REMOVE_COUPON')
    },
    onResponse(evt) {
      if(evt === true) {
        this.loginModal = false
        this.checkout()
      }
    },
    checkout() {
      if(this.config.is_guest_checkout || this.user) {
        this.$router.push({name: 'Checkout'})
      } else {
        this.loginModal = true
      }
    },
    directCheckout() {
      if(this.isCanCheckoutDirectWithShipping) {
        this.$router.push({name: 'CheckoutWithShipping'})
      } else {
        this.directCheckoutModal = true
      }
    },
    incrementQty(cart) {
      if(parseInt(cart.quantity) >= parseInt(cart.product_stock)) return
      let qty = parseInt(cart.quantity)+1
      if(!this.session_id) return
      this.$store.dispatch('cart/updateCart', { 
        sku: cart.sku, 
        quantity: qty, 
        session_id: this.session_id
      })
    },
    decrementQty(cart) {
      if(parseInt(cart.quantity) <= 1 ) return
      let qty = parseInt(cart.quantity)-1
      if(!this.session_id) return
      this.$store.dispatch('cart/updateCart', { 
        sku: cart.sku, 
        quantity: qty, 
        session_id: this.session_id
      })
    },
    removeCart(cart) {
      this.$store.dispatch('cart/removeCart', {
        sku: cart.sku, 
        session_id: cart.session_id
      })
    },
  },
  meta() {
    return {
      title: 'Keranjang Belanja'
    }
  }
}
</script>

<style scoped>
.cart-coupon .q-field__control {
    height: 50px;
}
</style>