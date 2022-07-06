<template>
  <div>
    <q-form @submit.prevent="submitOrder">
      <q-card class="full-width">
        <div class="text-weight-bold q-py-sm q-px-md bg-green-6 text-white text-md">Checkout Whatsapp</div>
        <q-card-section>
          <q-input 
          dense
          v-model="form.customer_name" 
          label="Nama Anda*"
          :rules="[val => val && val.length > 0 || 'Wajib diisi']"
          />
          <q-input 
          dense
          v-model="form.customer_whatsapp" 
          label="Nomor Whatsapp*"
          placeholder="081234567890"
          :rules="[val => val && val.length > 0 || 'Wajib diisi']"
          />
          <q-input 
          dense 
          label="Alamat" 
          v-model="form.address"
          :rules="[val => val && val.length > 0 || 'Wajib diisi']"
          ></q-input>
          <div class="q-pa-sm bg-grey-2 q-mt-sm" v-html="cartFormat"></div>
        </q-card-section>
        <q-card-actions class="justify-end q-pa-md">
            <q-btn unelevated color="grey-3" text-color="green-7" type="button" :disabled="loading" label="Batal" @click.prevent="$emit('close')" no-caps></q-btn>
            <q-btn type="submit" :loading="loading" :disabled="!canDirectCheckout" unelevated label="Order Sekarang" color="green-6" no-caps></q-btn>
        </q-card-actions>
      </q-card>
    </q-form>
    <q-dialog v-model="errorModal">
      <q-card>
        <q-card-section>
          <div class="text-md">Error</div>
          <div class="q-mt-sm">
            Silahkan masukkan nomor whatsapp yang benar.
          </div>
          <div class="q-mt-md flex justify-end">
            <q-btn label="OK" color="blue-7" unelevated flat @click="errorModal = false"></q-btn>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'CartBlock',
  data() {
    return {
      loading: false,
      cartFormat: '',
      errorModal: false,
      form: {
        customer_name: '',
        customer_whatsapp: '',
        address: '',
      }
    }
  },
  computed: {
    ...mapState({
      config: state => state.config,
      shop: state => state.shop,
      carts: state => state.cart.carts,
    }),
    session_id() {
      return this.$store.state.session_id
    },
    canDirectCheckout() {
      return this.carts.length && this.form.customer_name && this.form.customer_whatsapp && this.form.address && this.shop.phone ? true : false
    }
  },
  mounted() {
    if(this.carts.length) {
      let str = 'Pesanan Anda:<br>';
       this.carts.forEach(el => {
         str +=  `<b>${el.quantity}x</b> ${el.name}<br/> (@) ${this.moneyIDR(el.price)}<br>`
        })
        str += `<br><b>Total = ${this.moneyIDR(this.sumTotal())}</b>`
        this.cartFormat = str
    }
   
  },
  methods: {
    submitOrder() {
      this.checkInputPhone()

      if(!this.canDirectCheckout) return

      let str = `Halo kak, saya mau order:\n\n`

      let whatsappUrl = 'https://api.whatsapp.com'
      let shopPhone = this.formatPhoneNumber(this.shop.phone)
  
      let items = this.carts
      let numb = 1;
      items.forEach(el => {
        str +=  `*${numb}. ${el.name}*\nJumlah: ${el.quantity}\nHarga (@): ${this.moneyIDR(el.price)}\nHarga Total: ${this.moneyIDR(el.quantity*el.price)}\n\n`
        numb ++
      })

      str += `Total: *${this.moneyIDR(this.sumTotal())}*\n------------------------\n\n*Nama:*\n ${this.form.customer_name} (${this.form.customer_whatsapp})\n\n*Alamat:*\n${this.form.address}\n\n`

      let link = whatsappUrl+'/send?phone=' + shopPhone + '&text=' + encodeURI(str);

      setTimeout(() => {
        this.$emit('close')
      }, 7000)
      setTimeout(() => {
        this.$store.dispatch('cart/clearCart', this.session_id)
      }, 20000)

      window.open(link, '_blank');


    },
    sumQty() {
      if(this.carts.length > 1) {
        let q = [];
        this.carts.forEach(el => {
          q.push(el.quantity)
        });
        return q.reduce((a,b) => a + b)
      }
      return this.carts[0].quantity
    },
    sumSubtotal() {
      if(this.carts.length > 1) {
        let j = [];
        this.carts.forEach(el => {
          j.push(el.quantity*el.price)
        });
        return j.reduce((a,b) => a + b)
      }
      return this.carts[0].quantity * this.carts[0].price
    },
    sumWeight() {
      if(this.carts.length > 1) {
        let w = [];
        this.carts.forEach(el => {
          w.push(el.weight*el.quantity)
        });
        return w.reduce((a,b) => a + b)
      }
      return this.carts[0].quantity * this.carts[0].weight
    },
    sumTotal () {
      return this.sumSubtotal()
    },
    formatPhoneNumber(number) {
      let formatted = number.replace(/\D/g,'')

      if(formatted.startsWith('0')) {
        formatted = '62' + formatted.substr(1)
      }

      return formatted;
    },
    checkInputPhone() {
      this.form.customer_whatsapp = this.form.customer_whatsapp.replace(/\D/g,'')
      if(!this.checkPhoneNumber(this.form.customer_whatsapp)) {
        this.$q.dialog({
          message: 'Silahkan masukkan nomor whatsapp yang benar.'
        })
        this.form.customer_whatsapp = ''
      }
    },
    checkPhoneNumber(formatted) {

      if(formatted.length > 9 ) {
        
        if(formatted.startsWith('08') || formatted.startsWith('628')) {
          return true
        } 
      }
    },
    money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },

  }
}
</script>

<style>

</style>