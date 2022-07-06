<template>
  <q-page class="flex flex-center relative">
    <q-header class="text-primary bg-white">
      <q-toolbar dense>
        <q-btn :to="{ name: 'Login'}"
          flat round dense
          icon="arrow_back" />
       <q-toolbar-title class="text-weight-medium">Lupa Password</q-toolbar-title>
      </q-toolbar>
    </q-header>
    <div style="max-width: 420px;width:100%;" class="q-pa-sm">
      <div class="flex justify-center">
        <img v-if="shop && shop.logo_path" :src="shop.logo" style="width:auto;height:75px;object-fit:contain;max-width:150px;" />
        <img v-else src="/icon/logo.png" style="width:auto;height:75px;object-fit:contain;max-width:150px;" />
      </div>
    <div class="text-red q-pb-sm text-center" v-if="errors.email">{{ errors.email[0] }}</div>
      <q-card flat class="q-pt-sm" style="background:rgb(255 255 255 / 71%);">
        <q-card-section>
          <div class="text-grey-7 q-pa-sm" v-if="!isHasRequest">
           Lupa kata sandi? Silahkan masukan email yang terdaftar di situs ini, sistem akan mengirimkan kode token ke alamat email anda. 
            </div>
            <div class="text-grey-7 q-pa-sm" v-if="isHasRequest">
              Anda sudah membuat permintaan reset password, silahkan buka email anda.
              <router-link no-caps flat class="text-green-7" :to="{name: 'ResetPassword'}">Klik disini untuk memasukan kode token</router-link>
            </div>
          <q-form @submit.prevent="submit" class="q-gutter-y-md q-pa-sm">
             
          <q-input 
            v-model="form.email" 
            label="Email*"
            color="grey-6"
            type="email"
            dense
            :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
            >
            <template v-slot:prepend>
              <q-icon name="email" />
            </template>
          </q-input>

          <div class="column">
            <q-btn :loading="isLoading" 
            type="submit" color="primary" padding="sm lg"
            >Kirim</q-btn>
          </div>
          </q-form>
          <div class="text-center q-mt-sm"> 
            <q-btn :disabled="isLoading" no-caps flat color="green-7" :to="{name: 'Login'}">Kembali ke halaman login</q-btn>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
import { Notify } from 'quasar'
export default {
  name: 'pageForgotPassword',
  data () {
    return {
      isPwd: true,
      teks: '',
      newTeks: '',
      form: {
        email: '',
        token: '',
        password: '',
        passwod_confirmation: ''
      }
    }
  },
  computed: {
    errors() {
      return this.$store.state.errors
    },
    isLoading() {
      return this.$store.state.loading
    },
    isHasRequest() {
      return localStorage.getItem('is_reqpwd') ? true : false
    },
    shop() {
      return this.$store.state.shop
    }
  },
  methods: {
    ...mapActions('user', ['requestPasswordToken']),
    submit() {
      let self = this
      this.$store.commit('SET_LOADING', true)
      this.requestPasswordToken(this.form).then(response => {
        if(response.status == 200) {
          if(response.data.OK == true) {
            localStorage.setItem('is_reqpwd', true)
            this.form.email = ''
            Notify.create({
              type: 'positive',
              message: 'Berhasil membuat permintaan request kata sandi, Silahkan buka Email.'
            })
            self.$router.push({name: 'ResetPassword'})
          } else {
            this.$q.notify({
              type: 'negative',
              message: response.data.message
            })
          }
        }
      })
      .finally(() => {
        this.$store.commit('SET_LOADING', false)
      })
    }
  }
}
</script>
