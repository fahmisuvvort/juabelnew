<template>
  <div>
    <div id="shipping-address">
      <div class="text-md q-px-sm q-pb-xs">Penerima</div>
      <div class="q-gutter-y-md q-pa-sm">
        <q-input
        filled
        square
        stack-label
        label="Nama Penerima"
         :value="$store.state.order.formOrder.customer_name"
        @input="inputNameField"
        />
        <q-input
        v-if="canEmail"
        filled
        square
        stack-label
        type="email"
        required
        label="Alamat Email"
        :value="$store.state.order.formOrder.customer_email"
        @input="inputEmailField"
        />
        <q-input
        label="No ponsel / Whatsapp"
        filled
        square
        stack-label
        :value="$store.state.order.formOrder.customer_phone"
        @input="inputPhoneField"
        />
        </div>
        <div class="q-px-sm q-pt-md q-pb-xs">
          <div class="q-pb-md">
            <div class="text-md q-mb-sm">Alamat</div>
            <div class="bg-green-1 q-pa-sm text-green-10">
              <div style="font-size:13px;">Dikirim dari {{ originAddressFormat }}.</div>
              <div v-if="codAvailable" style="font-size:13px;">Pengiriman ke {{ codAvailable }} bisa diantar (COD)</div>
            </div>
          </div>
            <template v-if="!readyAddressBlock">
              <div class="q-gutter-y-md q-mb-md">
                <q-list v-if="address_subdistrict">
                  <q-item class="bg-grey-2">
                    <q-item-section>{{ destinationAddressFormat(address_subdistrict) }}</q-item-section>
                    <q-item-section side>
                      <q-btn dense flat no-caps color="red" @click="clearAddress">
                      <q-icon name="close" size="19px"></q-icon>
                      <span>Ganti</span>
                      </q-btn>
                        </q-item-section>
                    </q-item>
                </q-list>
                <div v-else>
                  
                  <q-input filled square placeholder="Ketik kecamatan tujuan" ref="search" v-model="searchSubdistrictKey" debounce="500" @input="findSubdistrict" :loading="isSearching"></q-input>
                  <transition
                      appear
                      enter-active-class="animated fadeIn"
                      leave-active-class="animated fadeOut"
                    >
                    <div class="relative bg-grey-1" v-show="isSearching || searchReady">
                      <q-list style="min-height:70px;max-height:300px;overflow-y:auto;" v-if="searchAvailable">
                        <q-item v-for="item in subdistrictOptionsData" :key="item.id" clickable @click="selectSubdistrict(item)">
                          <q-item-section>
                            <q-item-label>{{ destinationAddressFormat(item) }}</q-item-label>
                          </q-item-section>
                        </q-item>
                      </q-list>
                      <div v-else class="text-red-7 q-pa-md">kecamatan {{ searchSubdistrictKey }} tidak ditemukan</div>
                      <!-- <q-inner-loading :showing="isSearching"></q-inner-loading> -->
                    </div>
                  </transition>
                </div>
                <q-input v-if="address_subdistrict" filled square stack-label v-model="address_street" label="Alamat jalan / dusun / desa / Rt / Rw" @input="setAddress" debounce="100"/>

              </div>
            </template>
            <transition
              appear
              enter-active-class="animated fadeIn"
              leave-active-class="animated fadeOut"
            >
            <div v-if="formOrder.address">
              <div class="flex justify-between items-center q-pb-sm"> 
                <div class="text-grey-8">Detil Alamat Pengiriman</div>
                <q-btn flat v-if="readyAddressBlock" no-caps unelevated color="blue-7" padding="2px 12px" label="Edit Alamat" @click="changeNewAddress"></q-btn>
              </div>
              <div class="q-pa-md bg-grey-2" v-html="formOrder.address"></div>
            </div>
            </transition>
        </div>
     
    </div>
    <div class="q-px-sm q-pt-lg">
      <div class="text-md">Kurir</div>
    </div>
    <div id="cod" v-if="codItem" class="q-mb-sm">
      <div class="q-pa-sm">Pengiriman oleh penjual</div>
      <q-list class="q-px-sm">
        <q-item @click="selectCostCod(codItem)" clickable class="bg-grey-2">
          <q-item-section avatar>
            <q-icon :name="isSelectedCostCod ? 'radio_button_checked' : 'radio_button_unchecked'" :color="isSelectedCostCod ? 'green-6' : 'grey-6'"></q-icon>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ destinationAddressFormat(codItem) }}</q-item-label>
            <q-item-label>Ongkir : {{ (codItem.price && parseInt(codItem.price) > 0) ? moneyIDR(parseInt(codItem.price)) : 'Gratis'}} </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
    <div id="courier" ref="courier" class="">
      <div class="q-pa-sm">
        <div>Pengiriman via Ekspedisi</div>
        <div class="q-pt-sm">
          <q-select 
            :disable="!canSelectCourier"
            filled
            square
            stack-label
            label="Pilih Kurir"
            :options="couriers"  
            v-model="formGetCost.courier" 
            emit-value
            map-options
            @input="courierSelected"
            />
        </div>
      </div>
      <q-list v-if="shippingCost.ready">
        <template v-if="shippingCost.costs.length">
        <q-item v-for="item in shippingCost.costs" :key="item.service" v-ripple @click="selectCost(item)" clickable>
          <q-item-section avatar>
            <q-icon :name="isSelectedCost && isSelectedCost.service == item.service? 'radio_button_checked' : 'radio_button_unchecked'" :color="isSelectedCost && isSelectedCost.service == item.service? 'green-6' : 'grey-6'"></q-icon>
          </q-item-section>
          <q-item-section>
            <q-item-label>Servis : {{ item.service }}</q-item-label>
            <q-item-label>Deskripsi : {{ item.description }}</q-item-label>
            <q-item-label>Ongkir : {{ moneyIDR(item.cost[0].value)}}</q-item-label>
            <q-item-label>Etd: {{ item.cost[0].etd }} day</q-item-label>
          </q-item-section>
        </q-item>
        </template>
        <q-item v-else>
          <q-item-section>
            <q-item-label class="text-red-5 q-pa-lg">Ongkos kirim tidak ditemukan, silahkan ganti dengan kurir yang lain</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
      <div ref="courier_skeleton">
      <q-list v-if="loading" >
        <q-item v-for="i in 3" :key="i">
          <q-item-section avatar top>
            <div class="q-pa-sm">
            <q-skeleton width="20px" height="20px" class="round"></q-skeleton>
            </div>
          </q-item-section>
          <q-item-section>
            <q-skeleton type="text" width="80px"></q-skeleton>
            <q-skeleton type="text" width="180px"></q-skeleton>
            <q-skeleton type="text" width="110px"></q-skeleton>
            <q-skeleton type="text" width="90px"></q-skeleton>
          </q-item-section>
        </q-item>
      </q-list>
      </div>
    </div>
    
    <q-dialog v-model="useDataUserPrompt">
        <q-card style="max-width:400px;">
          <q-card-section>
            <div style="font-size:17px;font-weight:500;margin-bottom:6px;">Halo kak,</div>
            <div>Kami menemukan data alamat tersimpan dari order sebelumnya. Apakah akan menggunakan data tersebut?</div>
          </q-card-section>
          <q-card-actions class="justify-end">
            <q-btn size="12px" outline color="primary" label="Tidak" @click="closeModalAddress"></q-btn>
            <q-btn size="12px" unelevated color="primary" label="Ya Gunakan" @click="setDataUser"></q-btn>
          </q-card-actions>
        </q-card>
      </q-dialog>
  </div>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'ShippingAddress',
  props: {
    canEmail: {
      type: Boolean,
      default: false
    },
    isModalAddress: {
      type: Boolean,
      default: false
    },
    formData: Object
  },
  data() {
    return {
      loading: false,
      costNotFound: false,
      readyAddressBlock: false,
      useDataUserPrompt: false,
      isSelectedCost: null,
      isSelectedCostCod: null,
      form: {
        user_id: '',
        customer_name:'',
        customer_email: '',
        customer_phone: '',
        address: '',
        shipping_courier_name:'',
        shipping_cost: 0,
        shipping_courier_service:''
      },
      formGetCost: {
        origin: '',
        destination: '',
        weight: '',
        courier: '',
      },
      shippingCost: {
        code:'',
        name: '',
        costs: [],
        ready: false
      },
      address_street: '',
      userAddressData: {
        destination: '',
        address: ''
      },
      searchSubdistrictKey: '',
      isSearching: false,
      searchAvailable: true,
      searchReady:false,
      subdistrictOptionsData: [],
      address_subdistrict: null,
      timeout: null
    }
  },
  watch: {
    isModalAddress: function(evt) {
      this.useDataUserPrompt = evt
    }
  },
  computed: {
    user() {
      return this.$store.state.user.user
    },
    formOrder() {
      return this.$store.getters['order/getFormOrder']
    },
    currentWarehouse() {
      if(this.formOrder.seller) {
        return this.formOrder.seller.warehouse
      }else {
        return this.config.warehouse_address
      }
    },
    originAddressFormat() {
      return `${this.currentWarehouse.subdistrict_name} - ${this.currentWarehouse.city}, ${this.currentWarehouse.province}`
    },
    couriers() {
      let n = [];
      n.push({label: 'Pilih', value: ''})

      if(this.config) {

        let av = this.config.rajaongkir_couriers.split(',')
        av.forEach(function(el) {
          n.push({label: el.toUpperCase(), value: el})
        })
      }
      
      return n
      
    },
    config() {
      return this.$store.state.config
    },
    canSelectCourier() {
      if(this.formGetCost.destination && this.formGetCost.weight && this.formGetCost.origin){
        return true
      } else {
        return false
      }
    },
    canGetCost() {
      if(this.formGetCost.destination && this.formGetCost.courier && this.formGetCost.weight && this.formGetCost.origin){
        return true
      } else {
        return false
      }
    },
    codItem() {
      if(this.address_subdistrict) {
        if(this.config && this.config.cod_list && this.config.cod_list.length) {
          let h = this.config.cod_list.find(el => el.subdistrict_id == this.address_subdistrict.subdistrict_id)
          if(h != undefined) {
            return h
          } else {
            return null
          }
        } else {
          return null
        }

      } else {
        return null
      }
    },
    codAvailable() {
      let codStr = []
      if(this.config.cod_list.length){

        this.config.cod_list.forEach(eld => {
          codStr.push(`${eld.subdistrict_name}`)
        })
      }
      return codStr.join(', ')
    }
  },
  mounted() {
    this.setDataGetCost()
    if(this.user) {
     this.commitFormOrder('customer_name', this.user.name)
     this.commitFormOrder('customer_email', this.user.email)
     this.commitFormOrder('customer_phone', this.user.phone)
     this.commitFormOrder('user_id', this.user.id)
    }
  },
  methods: {
    inputNameField(val) {
      this.commitFormOrder('customer_name', val)
    },
    inputPhoneField(val) {
      this.commitFormOrder('customer_phone', val)
    },
    inputEmailField(val) {
      this.commitFormOrder('customer_email', val)
    },
    commitFormOrder(key, value) {
      this.$store.commit('order/SET_FORM_ORDER', {key: key, value: value})

      clearTimeout(this.timeout);

      this.timeout = setTimeout(() => {
        this.$emit('checkStep')

      }, 500)
    },
    destinationAddressFormat(obj) {
      return `${obj.subdistrict_name} - ${obj.type} ${obj.city}, ${obj.province}`
    },

    selectCostCod(item) {

      this.isSelectedCost = null

       this.commitFormOrder('payment_name',  'COD')
       this.commitFormOrder('payment_method',  'COD')
       this.commitFormOrder('payment_code',  '')
       this.commitFormOrder('payment_type',  'COD')
       this.commitFormOrder('shipping_courier_name',  'COD')
       this.commitFormOrder('shipping_courier_service',  'COD')
       this.commitFormOrder('shipping_cost',  item.price? parseInt(item.price) : 0)

      this.isSelectedCostCod = item

    },
    selectCost(item) {

      this.isSelectedCostCod = null
      this.isSelectedCost = item

      this.commitFormOrder('shipping_courier_name', this.shippingCost.name)
      this.commitFormOrder('shipping_courier_service', item.service)
      this.commitFormOrder('shipping_cost', item.cost[0].value)

    },
    changeNewAddress() {
      this.clearAddress()
      this.formGetCost.courier = ''
      
      this.formGetCost.destination = ''

      this.commitFormOrder('address', '')
      
      this.readyAddressBlock = false
      this.clearSelectedCost()

      setTimeout(() => {
        this.$refs.search.focus()
      },300)
    },
    closeModalAddress() {
      this.useDataUserPrompt = false
      this.$emit('closeModal')
    },
    clearAddress() {
      this.address_street = ''
      this.commitFormOrder('address', '')
      this.searchSubdistrictKey = '';
      this.address_subdistrict = null
      this.subdistrictOptionsData = []
      this.searchReady = false
      this.formGetCost.destination = ''
      this.formGetCost.courier = ''
      this.clearSelectedCost()
      setTimeout(() => {
        this.$refs.search.focus()
      },300)

    },
    selectSubdistrict(item) {
    
      this.address_subdistrict = item
      this.searchSubdistrictKey = ''

      this.userAddressData.destination = item
      this.formGetCost.weight = this.formOrder.weight

      if(this.config.rajaongkir_type == 'pro') {

        this.formGetCost.origin = this.currentWarehouse.subdistrict_id
        this.formGetCost.destination = item.subdistrict_id
        this.formGetCost.destinationType = 'subdistrict'
        this.formGetCost.originType = 'subdistrict'

      } else {

        this.formGetCost.origin = this.currentWarehouse.city_id
        this.formGetCost.destination = item.city_id
        this.formGetCost.weight = this.formOrder.weight

      }

      this.getCost()
    },
    findSubdistrict() {
      this.subdistrictOptionsData = []
      this.searchAvailable = true
      this.searchReady = false
      if(this.searchSubdistrictKey.length < 3) return
      this.isSearching = true
      Api().get('shipping/findSubdistrict/'+ this.searchSubdistrictKey)
      .then(response => {
        if(response.status == 200) {
          if(response.data.success) {

            this.subdistrictOptionsData = response.data.results
            this.searchAvailable = response.data.results.length ? true : false

          } else {
            this.$q.notify({
              type: 'negative',
              message: response.data.message
            })
          }
        }
      })
      .finally(() => {
         this.isSearching = false
         this.searchReady = true
      })
    },

    setDataUser() {
      
      let data = JSON.parse(localStorage.getItem('user_data'))

      this.address_street = data.address_street
      this.address_subdistrict = data.address_subdistrict

      this.formGetCost.destination = data.address_subdistrict.city_id
      this.formGetCost.origin = this.currentWarehouse.city_id
      this.formGetCost.weight = this.formOrder.weight
      
       if(this.config.rajaongkir_type == 'pro') {
         
        this.formGetCost.destination = data.address_subdistrict.subdistrict_id
        this.formGetCost.origin = this.currentWarehouse.subdistrict_id
        this.formGetCost.destinationType = 'subdistrict'
        this.formGetCost.originType = 'subdistrict'

      }

      this.setAddress()

      this.useDataUserPrompt = false
      this.readyAddressBlock = true

    },

    courierSelected(evt) {
      if(!evt) {
        this.clearSelectedCost()
      }
     
      if(evt == 'cod') {
        this.commitFormOrder('shipping_courier_name', 'COD')
        this.commitFormOrder('shipping_courier_service', 'COD')
        this.commitFormOrder('shipping_cost', 0)

        this.clearSelectedCost()
        
      } else {
        this.commitFormOrder('shipping_courier_name',  this.formGetCost.courier)
        this.getCost()
      }
    },
    clearSelectedCost() {

      this.shippingCost.code = ''
      this.shippingCost.name = ''
      this.shippingCost.costs = []
      this.shippingCost.ready = false
      this.isSelectedCost = null
      this.isSelectedCostCod = null

      this.commitFormOrder('shipping_courier_name', '')
      this.commitFormOrder('shipping_courier_service', '' )
      this.commitFormOrder('shipping_cost', 0 )

    },
    getCost() {
      this.shippingCost.ready = false
      this.costNotFound = false
      this.clearSelectedCost() 
      if(this.canGetCost) {
        this.scrollToBottom()
        this.loading = true
        Api().post('shipping/getCost', this.formGetCost).then(response => {
          if(response.status == 200) {

            let data = response.data.results[0];
              this.shippingCost.code = data.code
              this.shippingCost.name = data.name
              this.shippingCost.costs = data.costs

            if(!data.costs.length) {
              this.costNotFound = true
            }
          }
        }).catch(() => {
          this.costNotFound = true

        })
        .finally(() => {
           this.shippingCost.ready = true
           this.loading = false
        })
      }
    },
    setDataGetCost() {

      this.formGetCost.weight = this.formOrder.weight;

      if(this.config && this.config.can_shipping){

        this.formGetCost.origin = this.currentWarehouse.city_id

        if(this.config.rajaongkir_type == 'pro') {
          this.formGetCost.origin = this.currentWarehouse.subdistrict_id
          this.formGetCost.destinationType = 'subdistrict'
          this.formGetCost.originType = 'subdistrict'
        }

      } 
    },

    checkInputPhone() {
      let validPhone = this.formOrder.customer_phone.replace(/\D/g,'')

      if(!this.checkPhoneNumber(validPhone)) {
        this.$q.dialog({
          message: 'Silahkan masukkan nomor whatsapp yang benar.'
        })
        this.commitFormOrder('customer_phone', '')
      }
    },
    checkPhoneNumber(formatted) {

      if(formatted.length > 9 ) {
        
        if(formatted.startsWith('08') || formatted.startsWith('628')) {
          return true
        } 
      }
    },
    setAddress() {
      if(this.address_street && this.address_subdistrict) {
        let addr =  `${this.address_street} <br> ${this.address_subdistrict.subdistrict_name} - ${this.address_subdistrict.type} ${this.address_subdistrict.city} <br> ${this.address_subdistrict.province}`

        this.commitFormOrder('address', addr)

      }

    },
    saveDataUser() { 
      if(this.address_street && this.address_subdistrict) {
        localStorage.setItem('user_data', JSON.stringify({
          address_street: this.address_street,
          address_subdistrict: this.address_subdistrict
        }))
      }
    },
    scrollToBottom () {
      setTimeout(() => {
        var elem = this.$refs.courier;
        elem.scrollIntoView({behavior: 'smooth'})
      }, 300)
    }
  }
}
</script>

<style>

</style>