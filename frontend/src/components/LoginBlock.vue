<template>
    <q-card flat class="bg-white" style="max-width:420px;width:100%;">
      <div class="q-py-sm q-px-md row justify-between items-center">
      <div class="text-md text-weight-bold text-grey-8">{{ isRegister? 'Register' : 'Login'}}</div>
        <q-btn @click.prevent="onClose" icon="close" round flat padding="xs"></q-btn>
      </div>
      <q-separator />
      <q-card-section style="max-height:70vh;" class="scroll">
        <q-form @submit.prevent="submit" class="q-gutter-y-xs">
          <template v-if="isRegister">
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
              label="Alamat email *"
              dense
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
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
          </template>
          <template v-if="!isRegister">
            <q-input
              v-model="form.email"
              color="grey-6"
             label="Email atau No Ponsel*"
              dense
              lazy-rules
              :rules="[ val => val && val.length > 0 || 'Wajib diisi']"
              >
              <template v-slot:prepend>
                <q-icon name="email" />
              </template>
            </q-input>
          </template>

          <q-input 
          v-model="form.password" 
          label="Password *"
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
        <template v-if="isRegister">
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
        </template>
      <div class="column q-gutter-y-sm">
        <q-btn :loading="isLoading" unelevated
        type="submit" color="primary" padding="sm lg"
        >{{ isRegister? 'Daftar' : 'Login'}}</q-btn>

      </div>
        </q-form>
        <div class="column q-gutter-y-sm text-center q-mt-xs">
          <div v-if="isRegister">Sudah punya akun <q-btn no-caps color="green-7" padding="xs" flat :disabled="isLoading" label="Login Disini" @click="formType ='login'"></q-btn></div>
          <div v-if="!isRegister">
            <div>
              Belum punya akun <q-btn no-caps color="green-7" padding="xs" flat :disabled="isLoading" label="Daftar Disini" @click="formType ='register'"></q-btn>
            </div>
            <div>
              <q-btn no-caps color="green-7" padding="xs" flat :disabled="isLoading" label="Lupa password?" :to="{ name: 'ForgotPassword'}"></q-btn>
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
</template>

<script>
import { Api } from 'boot/axios'
import Cookies from 'js-cookie';
export default {
  data() {
    return {
      isLoading: false,
      isPwd: true,
      loading: false,
      formType: 'login',
      form: {
        name: '',
        email: '',
        password: '',
        phone: '',
        password_confirmation: '',
        device_name: 'APP'
      }
    }
  },
  computed: {
    isRegister() {
      return this.formType == 'register'
    }
  },
  methods: {
    onClose() {
      this.$emit('onClose');
    },
    submit() {
      this.isLoading = true
      if(this.formType == 'login') {
        Api().post('user/login', this.form).then(response => {
          if(response.status == 200) {
            this.$store.commit('user/SET_USER', response.data.results)
            Cookies.set('__token', response.data.token, { expires: 1 })
          }
          this.$emit('onResponse', true)
        })
        .finally(() => {
          this.isLoading = false
        })
      }
      if(this.formType == 'register') {
        Api().post('user/register', this.form).then(response => {
          if(response.status == 201) {
            this.$store.commit('user/SET_USER', response.data.results)
            Cookies.set('__token', response.data.token, { expires: 1 })
            this.$emit('onResponse', true)
          }
        })
        .finally(() => {
          this.isLoading = false
        })
      }
    }
  }
}
</script>
