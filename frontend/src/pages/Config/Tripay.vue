<template>
  <div class="q-pa-md bg-white box-shadow">
    <div class="flex items-center justify-between q-mb-xs">
      <div class="text-md text-weight-bold">Tripay Payment Gateway</div>
      <div>
        <q-toggle v-model="form.is_payment_gateway" :label="form.is_payment_gateway? 'Active' : 'Disabled'" left-label @input="checkIsReady" color="green-7"></q-toggle>
      </div>
    </div>
    <q-form @submit.prevent="updateDate">
      <div class="q-mb-md text-grey-7 text-caption">
        <div>Pengaturan Tripay payment Gateway</div>
        <div>Silahkan daftar di tripay.co.id untuk mendapatkan Kredensial</div>
      </div>
      <div class="q-gutter-y-sm">
        <q-select label="ENV MODE" filled :options="tripayModes" v-model="form.tripay_mode"></q-select>
        <q-input
        filled
        v-model="form.tripay_merchant_code"
        label="Tripay MERCHANT CODE"
        />
        <q-input
        filled
        v-model="form.tripay_api_key"
        label="Tripay API KEY"
        />
        <q-input
        filled
        v-model="form.tripay_private_key"
        label="Tripay PRIVATE KEY"
        />
      </div>
      <div class="flex justify-end q-mt-md">
        <q-btn unelevated size="12px" type="submit" label="Simpan Tripay" color="blue-7"></q-btn>
      </div>
    </q-form>
  </div> 
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data () {
    return {
      tripayModes: ['sanbox', 'production'],
      form: {
        tripay_mode: '',
        tripay_api_key: '',
        tripay_private_key: '',
        tripay_merchant_code: '',
        is_payment_gateway: false
      },
    }
  },
  computed: {
    config: function() {
      return this.$store.state.config
    }
  },
  mounted() {
    if(this.config) {
      this.setConfig()
    }
  },
  methods: {
    setConfig() {
      if(this.config) {
        this.form.tripay_mode = this.config.tripay_mode
        this.form.tripay_api_key = this.config.tripay_api_key
        this.form.tripay_private_key = this.config.tripay_private_key
        this.form.tripay_merchant_code = this.config.tripay_merchant_code
        this.form.is_payment_gateway = this.config.is_payment_gateway
      }
    },
    updateDate() {
      let data = {...this.config, ...this.form}
      Api().post('config',  this.form).then(() => {
        this.showNotify()
        this.$store.dispatch('getAdminConfig')
      }).catch(() => {
        this.showNotify(error)    
      })
    },
    checkIsReady(evt) {
      if(evt) {
        if(!this.isReady()) {
          this.form.is_payment_gateway = false
          this.$q.notify({
            type: 'negative',
            message: 'Silahkan lengkapi data kredensial untuk mengaktifkan payment gateway.'
          })
        }
      }
    },
    isReady() {
      return this.form.tripay_mode && this.form.tripay_api_key && this.form.tripay_private_key && this.form.tripay_merchant_code ? true : false
    },
    showNotify(error = '') {
      if(error) {
        this.$q.notify({
          type: 'negative',
          message: 'Gagal memperbarui data'
        })
      } else {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil memperbarui data'
        })
      }
      
    },
  }
}
</script>