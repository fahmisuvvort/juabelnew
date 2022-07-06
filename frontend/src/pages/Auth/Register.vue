<template>
  <q-page class="flex flex-center relative">
     <q-header class="text-primary bg-white">
       <q-toolbar>
         <q-btn :to="{name: 'Home'}"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-medium">Register</q-toolbar-title>
       </q-toolbar>
    </q-header>
    <div style="max-width: 450px;width:100%;" class="q-pa-sm">
      <div class="flex justify-center">
        <img v-if="shop && shop.logo_path" :src="shop.logo" style="width:auto;height:75px;object-fit:contain;max-width:150px;" />
        <img v-else src="/icon/logo.png" style="width:auto;height:75px;object-fit:contain;max-width:150px;" />
      </div>
      <div class="text-red q-pb-sm text-center" v-if="errors.email">{{ errors.email[0] }}</div>
      <q-card flat class="q-pt-sm" style="background:rgb(255 255 255 / 71%);">
        <q-card-section>
          <q-form @submit.prevent="submit" class="q-gutter-y-sm q-pa-md">
            <q-input
              v-model="form.name"
              color="grey-6"
              label="Nama Anda *"
              dense
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
              >
              <template v-slot:prepend>
                <q-icon name="account_circle" />
              </template>
            </q-input>
              <q-input
              v-model="form.email"
              color="grey-6"
              label="Email Anda *"
              dense
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Please type email']"
            >
              <template v-slot:prepend>
                <q-icon name="email" />
              </template>
            </q-input>
            <q-input
              v-model="form.phone"
              color="grey-6"
              label="No Ponsel / Whatsapp *"
              dense
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
              >
              <template v-slot:prepend>
                <q-icon name="phone" />
              </template>
            </q-input>
            
            <q-input 
            v-model="form.password" 
            label="Password *"
            color="grey-6"
            :type="isPwd ? 'password' : 'text'"
            dense
            :rules="[ val => val && val.length > 0 || 'Please type password']"
            >
            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility' : 'visibility_off'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
        <q-input 
          v-model="form.password_confirmation" 
          label="Konfirmasi Password *"
          color="grey-6"
          :type="isPwd ? 'password' : 'text'"
          dense
          :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
          >
          <template v-slot:prepend>
            <q-icon name="lock" />
          </template>
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility' : 'visibility_off'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
        <div class="column">
          <q-btn :loading="isLoading" 
          type="submit" color="primary" padding="sm lg" 
          >Daftar Sekarang</q-btn>

        </div>
          </q-form>
          <div class="column text-center q-mt-sm">
          <div>
          Sudah punya akun <q-btn no-caps color="green-7" padding="xs" flat :disabled="isLoading" label="Login Disini" :to="{ name: 'Login'}"></q-btn>
          </div>
        </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'PageLogin',
  data () {
    return {
      isPwd: true,
      form: {
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        device_name: 'APP'
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
    shop() {
      return this.$store.state.shop
    },
  },
  methods: {
    ...mapActions('user', ['register']),
    submit() {
      if(this.checkInputPhone()) {
        this.register(this.form)
      }
    },
    checkInputPhone() {
      this.form.phone = this.form.phone.replace(/\D/g,'')
      if(!this.checkPhoneNumber(this.form.phone)) {
        this.$q.notify({
          message: 'Silahkan masukkan nomor whatsapp yang benar.',
          type: 'negative'
        })
        this.form.phone = ''

        return false
      }
      return true
    },
    checkPhoneNumber(formatted) {

      if(formatted.length > 9 ) {
        
        if(formatted.startsWith('08') || formatted.startsWith('628')) {
          return true
        } 
      }
    },
  }
}
</script>
