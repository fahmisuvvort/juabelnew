<template>
  <q-page class="">
    <q-header class="text-primary bg-white box-shadow" reveal :reveal-offset="10">
        <q-toolbar>
          <q-btn @click="handleBackButton"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">{{ title }}</q-toolbar-title>
        </q-toolbar>
    </q-header>
    <div id="checkout" v-if="carts && carts.items.length" ref="top" class="q-pb-xl">
      <q-stepper
        v-model="step"
        keep-alive
        flat
        color="primary"
        alternative-labels
        animated
        >
          <q-step
          :name="1"
          title="Pengiriman"
          :done="step > 1"
          icon="local_shipping"
        >
          <shipping-address 
            canEmail 
            :isModalAddress="isAvailableOldAddress"
            @closeModal="isAvailableOldAddress = false"
            @checkStep="checkStepOk"
          />

        </q-step>

          <q-step
          :name="2"
          title="Pembayaran"
          :done="step > 2"
          icon="payments"
          v-if="formOrder.shipping_courier_name != 'COD'"
          >
         
          <payment-list 
            ref="paymentList" 
            :paymentSelected="paymentSelected" 
            :payments="paymentChanels" 
             @checkStep="checkStepOk"
             @onSelectPayment="onSelectPayment"
          />
          </q-step>

          <q-step
          :name="3"
          title="Review"
          :done="step > 3"
          icon="playlist_add_check"
          >
         <review-order 
         :paymentSelected="paymentSelected" 
         :noPayment="isCantPaymentStep"/>
          </q-step>

      </q-stepper>
    </div>
    <q-inner-loading :showing="loading">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    <div class="bg-white q-pa-md q-gutter-y-sm column" :class="{'sticky-bottom': $q.platform.is.desktop }">
      <q-btn :disabled="!isOk" v-if="step != 3 && formOrder.shipping_courier_name != 'COD'" @click="next" no-caps unelevated label="Langkah Selanjutnya" color="primary"></q-btn>
      <q-btn :disabled="!isOk" v-if="formOrder.shipping_courier_name == 'COD' && step != 3" @click="step = 3" no-caps unelevated label="Langkah Selanjutnya" color="primary"></q-btn>
      <q-btn :disabled="loading" v-if="step == 3" @click="submitOrder" no-caps unelevated label="Proses Pesanan" color="primary"></q-btn>
      <!-- <q-btn :disabled="step == 1" @click="prev" no-caps outline label="Kembali" color="primary"></q-btn> -->
    </div>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
import { mapActions } from 'vuex'
import ShippingAddress from './ShippingAddress.vue'
import PaymentList from './PaymentList.vue'
import ReviewOrder from './ReviewOrder.vue'
export default {
	components: { ShippingAddress, PaymentList, ReviewOrder },
  name: 'CheckoutPage',
  data () {
    return {
      isAvailableOldAddress: false,
      isOk: false,
      formLoading: false,
      paymentChanels: {
        localbanks: [],
        paymentGateway: []
      },
      step: 1,
      paymentSelected: null,
    }
  },
  watch: {
    step: function() {
      this.checkStepOk()
      this.toTop()
    },
  },
  computed: {
    formOrder() {
      return this.$store.getters['order/getFormOrder']
    },
    carts() {
      return this.$store.getters['cart/getCarts']
    },
    shop() {
      return this.$store.state.shop
    },
    config() {
      return this.$store.state.config
    },
    isCod() {
      return this.formOrder.shipping_courier_name == 'COD'
    },
    loading() {
      return this.$store.state.loading
      },
    isCantPaymentStep() {
      return this.formOrder.shipping_courier_name == 'COD' || !this.config.is_payment_gateway? true : false
    },
    title() {
      if(this.step == 1) return 'Pengiriman'
      if(this.step == 2) return 'Pembayaran'
      if(this.step == 3) return 'Review Order'
      return 'Checkout'
    },
    session_id() {
      return this.$store.state.session_id
    },
  },
  mounted() {
    if(!this.carts.items.length) {
      this.$router.push({ name: 'Cart'})
    }
    if(this.config && this.config.is_payment_gateway && ! this.paymentChanels.paymentGateway.length) {
      this.getPaymentChanel()
    }
    if(! this.paymentChanels.localbanks.length) {
      this.getLocalBanks()
    }
    this.checkDataUser()
    this.collectOrder()
  },
  methods: {
    ...mapActions('order', ['storeOrder']),
    collectOrder() {
      this.$store.commit('order/COLLECT_ORDER', this.carts)
    },
    checkDataUser() {
      if(localStorage.getItem('user_data')) {
        this.isAvailableOldAddress = true
      }
    },
    handleBackButton() {
      if(this.step > 1) {
        if(this.isCod) {
          this.step = 1
          } else {
          this.step -= 1
        }
      } else {
        this.$router.push({ name: 'Cart'})
      }
    },
    toTop() {
      setTimeout(() => {
        var elem = this.$refs.top;
        elem.scrollIntoView({behavior: 'smooth'})
      }, 500)
    },
    checkStepOk() {

      if(this.step == 1) { 
        if(this.formOrder.customer_name 
            && this.formOrder.customer_email 
            && this.formOrder.customer_phone 
            && this.formOrder.shipping_courier_name
            && this.formOrder.address) {
               if(this.isCod) {
                 this.isOk = true
              } else {
                this.formOrder.shipping_cost ?  this.isOk = true :  this.isOk = false
              }
            } else {
              this.isOk = false
            }
      } else if(this.step == 2) {
        if(this.formOrder.payment_name 
            && this.formOrder.address 
            && this.formOrder.payment_type) {
              this.isOk = true
          } else {
              this.isOk = false
          }
        } else {
              this.isOk = true
        }
      
    },
    onSelectPayment(item) {
      this.paymentSelected = item
    },

    submitOrder() {
      this.$store.commit('SET_LOADING', true)
      this.storeOrder(this.formOrder)
      .then(response => {

          this.$store.commit('SET_LOADING', false)
          this.$store.commit('cart/REMOVE_COUPON')

          if(response.status == 200) {

            this.$store.commit('order/SET_INVOICE', response.data.results)
            
            setTimeout(() => {
              this.$store.dispatch('cart/clearCart', this.session_id)
            }, 1000)

            if(this.formOrder.shipping_courier_name == 'COD') {
  
              this.directChekout(response.data.results)
              
            }
            
            this.$router.push({ name: 'UserInvoice', params: { order_ref: response.data.results.order_ref }})

            this.sendMessageNotification(response.data.results.order_ref)
          }
        })
        .catch(() => {
          this.ready = false
          this.$store.commit('SET_LOADING', false)
        })
    },
    sendMessageNotification(order_ref) {
      setTimeout(() => {
        Api().post('sendNotify', {url: this.getRoutePath(order_ref), order_ref: order_ref})
      },12000)
    },
    formatAddressCod(addr) {
      let arr = addr.split('<br>')
      return arr.join('\n')
    },
    directChekout(data) {

      let whatsappUrl = 'https://api.whatsapp.com'
      
      if(this.$q.platform.is.desktop) {
        whatsappUrl = 'https://web.whatsapp.com'
      }
      let whatsapp = this.formatPhoneNumber(this.shop.phone)

      let str = `Halo kak, saya mau order:\n\n`
    
      let items = data.items
      let numb = 1;
      items.forEach(el => {
        str +=  `*${numb}. ${el.name}*\n`
        if(el.note){
          str += `[${el.note}]\n`
        }
        str+= `Jumlah: ${el.quantity}\nHarga (@): ${this.moneyIDR(el.price)}\nHarga Total: ${this.moneyIDR(el.quantity*el.price)}\n\n`
        numb ++
      })

      str += `Subtotal: *${this.moneyIDR(data.order_subtotal)}*\nOngkir: *${this.moneyIDR(data.shipping_cost)}*\nDiskon: *${this.moneyIDR(data.discount)}*\nTotal: *${this.moneyIDR(data.order_total)}*\n------------------------\n\n*Nama:*\n ${data.customer_name} (${data.customer_whatsapp})\n\n*Alamat:*\n${this.formatAddressCod(data.shipping_address)}\n\n`

      str += `Metode Pembayaran: ${data.transaction.payment_name}\n\n`

      str += `Referensi Order:\n${this.getRoutePath(data.order_ref)}`

      let link = whatsappUrl+'/send?phone=' + whatsapp + '&text=' + encodeURI(str);

      window.open(link, '_blank');

    },
    formatPhoneNumber(number) {
      let formatted = number.replace(/\D/g,'')

      if(formatted.startsWith('0')) {
        formatted = '62' + formatted.substr(1)
      }

      return formatted;
    },
    getRoutePath(ref) {
      let props = this.$router.resolve({ 
        name: 'UserInvoice',
        params: { order_ref: ref },
      });

      return location.origin + props.href;
    },
    getLocalBanks() {
      let self = this
      Api().get('banks').then(response => {
        if(response.status == 200) {
          self.paymentChanels.localbanks = response.data.results
        }
      })
    },
    getPaymentChanel() {
      let self = this
      Api().get('tripay/payment-chanel').then(response => {
        if(response.status == 200) {
          self.paymentChanels.paymentGateway = response.data.data
        }
      })
    },
    next() {
      if(this.isCod) {
        this.step = 3
        } else {
        this.step += 1
      }
    },
    prev() {
      if(this.isCod) {
        this.step = 1
        } else {
        this.step -= 1
      }
    },
  
  },
  meta() {
    return {
      title: 'Checkout'
    }
  }
}
</script>
