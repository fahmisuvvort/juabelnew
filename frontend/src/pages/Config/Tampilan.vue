<template>
<q-card flat>
  <q-card-section>
    <div class="text-subtitle1 text-weight-bold">Basic</div>
    <div class="text-caption text-grey-7">Pengaturan tampilan produk dan social proof</div>
  </q-card-section>
  <q-list>
    <q-item>
      <q-item-section>
        <q-item-label>
          Tampilan produk beranda
        </q-item-label>
      </q-item-section>
      <q-item-section side>
        <div class="q-gutter-x-sm">
          <q-btn @click="changeHomeViewMode('grid')" label="Grid Mode" size="sm" unelevated :color="form.home_view_mode == 'grid' ? 'green-7' : 'grey-6'" dense icon="grid_view">
            <q-tooltip>
              Grid Mode
            </q-tooltip>
          </q-btn>
          <q-btn @click="changeHomeViewMode('list')" label="List Mode" size="sm" unelevated :color="form.home_view_mode == 'list' ? 'green-7' : 'grey-6'" dense icon="view_list">
            <q-tooltip>
              List Mode
            </q-tooltip>
          </q-btn>
        </div>
      </q-item-section>
    </q-item>
    <q-item>
      <q-item-section>
        <q-item-label>
         Tampilan produk katalog
        </q-item-label>
      </q-item-section>
      <q-item-section side>
        <div class="q-gutter-x-sm">
          <q-btn @click="changeProductViewMode('grid')" label="Grid Mode" size="sm" unelevated :color="form.product_view_mode == 'grid' ? 'green-7' : 'grey-6'" dense icon="grid_view">
            <q-tooltip>
              Grid Mode
            </q-tooltip>
          </q-btn>
          <q-btn @click="changeProductViewMode('list')" label="List Mode" size="sm" unelevated :color="form.product_view_mode == 'list' ? 'green-7' : 'grey-6'" dense icon="view_list">
            <q-tooltip>
              List Mode
            </q-tooltip>
          </q-btn>
        </div>
      </q-item-section>
    </q-item>
     <q-item class="q-mt-md">
      <q-item-section>
        <q-item-label class="text-weight-medium">Social Proof Popup</q-item-label>
         <div class="q-mb-sm text-caption text-grey-7 q-mt-sm">
           Pengaturan social proof notifikasi.
         </div>
      </q-item-section>
      <q-item-section side top>
        <q-toggle v-model="form.is_notifypro" :label="form.is_notifypro? 'ON' : 'OFF'" left-label color="green-6"></q-toggle>
      </q-item-section>
    </q-item>
    <q-item v-if="form.is_notifypro">
      <q-item-section>
        <q-input outlined dense label="Jeda Waktu Tayang" mask="###" :hint="'Popup akan tayang setiap ' + form.notifypro_interval +' Detik'" v-model="form.notifypro_interval"></q-input>
      </q-item-section>
      <q-item-section>
        <q-input outlined dense label="Durasi Penayangan" mask="###" :hint="'Popup akan tayang selama ' + form.notifypro_timeout +' Detik'" v-model="form.notifypro_timeout"></q-input>
      </q-item-section>
    </q-item>
  </q-list>
  <q-card-section class="flex justify-end">
    <q-btn unelevated size="12px" label="Simpan" color="blue-7" @click="saveTampilan"></q-btn>
  </q-card-section>
</q-card>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data () {
    return {
      form: {
        home_view_mode:'',
        product_view_mode: '',
        is_notifypro: false,
        notifypro_interval: 20,
        notifypro_timeout: 4,
      }
    }
  },
  computed: {
    config: function() {
      return this.$store.state.config
    }
  },
  mounted() {
    this.form.product_view_mode = this.config.product_view_mode
    this.form.home_view_mode = this.config.home_view_mode
    this.form.is_notifypro = this.config.is_notifypro
    this.form.notifypro_interval = this.config.notifypro_interval
    this.form.notifypro_timeout = this.config.notifypro_timeout
  },
  methods: {
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
    changeHomeViewMode(str) {
      this.form.home_view_mode = str
    },
    changeProductViewMode(str) {
      this.form.product_view_mode = str
    },
    saveTampilan() {
      let data = {...this.config, ...this.form}
      Api().post('config', this.form).then(response => {
        if(response.status == 200) {
          this.$store.dispatch('getAdminConfig')
        }
        this.showNotify()

      }).catch(() => {
        this.showNotify(error)
      })
    }
  }
}
</script>
