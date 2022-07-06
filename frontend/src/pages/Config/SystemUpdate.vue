<template>
  <q-card flat>
  <q-card-section>
    <div class="text-md text-weight-bold">System Update / Clear Cache</div>
    <div class="text-caption text-grey-7">Pengaturan sistem update dan cache situs, Pastikan anda mengerti apa yang anda lakukan</div>
  </q-card-section>
  <q-list class="q-pb-md">
    <q-item>
      <q-item-section>
        <q-item-label v-if="needUpdate">
          <q-badge color="green-7">{{ siteData }}</q-badge> Pending Update
        </q-item-label>
        <q-item-label v-else>Updated</q-item-label>
      </q-item-section>
      <q-item-section side>
        <q-btn :loading="loading" :disabled="!needUpdate" @click="updateSystem" :label="needUpdate? 'Update Now' : 'No updated found'" unelevated :color="needUpdate? 'blue-7' : 'grey-7'" size="12px"></q-btn>
      </q-item-section>
    </q-item>
    <q-item>
      <q-item-section>
        <q-item-label>Clear Cache</q-item-label>
      </q-item-section>
      <q-item-section side>
        <q-btn :loading="loadingClearChace" @click="clearCache" label="Clear Cache" unelevated color="blue-7" size="12px"></q-btn>
      </q-item-section>
    </q-item>
  </q-list>
  </q-card>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data() {
    return {
      siteData: 0,
      loading: false,
      loadingClearChace: false
    }
  },
  computed: {
    needUpdate() {
      return this.siteData == 0 ? false : true
    }
  },
  created() {
    this.getUpdate()
  },
  methods: {
    getUpdate() {
      Api().get('update').then(response => {
        if(response.status == 200) {
          this.siteData = response.data.results
        }
      })
    },
    updateSystem() {
      this.loading = true
      Api().post('update').then(response => {
        if(response.status == 200) {
          this.getUpdate()
          this.$q.notify({
           type: 'positive',
           message: 'Berhasil update system'
         })
        }
      }).catch(() => {
         this.$q.notify({
           type: 'negative',
           message: 'Gagal update system, silahkan hubungi pengembang aplikasi.'
         })
      }).finally(() => {
         this.loading = false
         setTimeout(() => {
           location.reload()
         }, 500)
         this.$router.push({name: 'Settings'})
      })
    },
    clearCache() {
       this.loadingClearChace = true
      Api().post('clearCache').then(response => {
        if(response.status == 200) {
         this.$q.notify({
           type: 'positive',
           message: 'Berhasil menghapus cache'
         })
        }
      }).catch(() => {
         this.$q.notify({
           type: 'negative',
           message: 'Gagal menghapus cache, silahkan ulangi lagi'
         })
      }).finally(() => {
         this.loadingClearChace = false
         this.$router.push({name: 'Settings'})
      })
    },

  }
}
</script>

<style>

</style>