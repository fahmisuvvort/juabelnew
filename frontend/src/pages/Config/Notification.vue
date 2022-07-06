<template>
  <q-card flat>
    <q-card-section>
      <div class="flex items-center justify-between">
        <div class="text-subtitle1 text-weight-bold">Telegram Notifikasi</div>
        <div class="q-px-sm rounded-borders text-white" :class="config && config.is_telegram_ready? 'bg-green-7' : 'bg-grey-6'">{{ config && config.is_telegram_ready? 'Active' : 'Disabled' }}</div>
      </div>
      <div class="text-caption text-grey-7">Notifikasi order untuk admin via telegram</div>
      <div class="text-caption text-grey-7">Silahkan buat bot di telegram untuk mendapatkan token, serta dapatkan user id di bot @infouserid</div>
     <q-form @submit.prevent="updateData">
      <div class="q-gutter-y-sm q-mt-md">
        <q-input
        filled
        v-model="form.telegram_bot_token"
        label="Telegram Bot Token"
        />
        <q-input
        filled
        v-model="form.telegram_user_id"
        label="Telegram user Id"
        />
      </div>
      <div class="flex justify-end q-mt-md">
        <q-btn unelevated size="12px" type="submit" label="Simpan Telegram" color="blue-7"></q-btn>
      </div>
    </q-form>
  </q-card-section>
  </q-card>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data() {
    return {
      form: {
        telegram_bot_token: '',
        telegram_user_id: ''
      }
    }
  },
  computed: {
    config: function() {
      return this.$store.state.config
    }
  },
  mounted() {
    this.setData()
  },
  methods: {
    updateData() {
      let data = {...this.config, ...this.form}
      Api().post('config',  this.form).then(() => {
        this.showNotify()
        this.$store.dispatch('getAdminConfig')
      }).catch(() => {
        this.showNotify(error)    
      })
    },
    setData() {
      if(this.config) {
        this.form.telegram_bot_token = this.config.telegram_bot_token
        this.form.telegram_user_id = this.config.telegram_user_id
      }
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

<style>

</style>