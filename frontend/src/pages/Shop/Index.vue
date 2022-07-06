<template>
  <q-page class="q-pb-xl">
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Pengaturan Toko
        </q-toolbar-title>
      
      </q-toolbar>
    </q-header>
    <div class="q-pa-md q-gutter-y-md">
      <div class="q-gutter-y-md">
        <q-input label="Nama Toko" v-model="form.name"></q-input>
        <q-input label="Nomor Whatsapp" v-model="form.phone" placeholder="0812*******"></q-input>
        <div class="q-my-xs text-red text-sm" v-if="errors.phone">Nomor Whatsapp harus berupa angka</div>
        <q-input autogrow label="Deskripsi Toko" v-model="form.description"></q-input>
        <div class="q-my-md">
          <div for="" class="text-grey-8 q-mb-sm">Alamat Tampilan Toko</div>
          <q-editor 
            v-model="form.address" 
            :toolbar="[
              ['bold', 'italic', 'underline']
            ]"
          ></q-editor>
        </div>
      </div>
     
      <div>
        <input type="file" class="hidden" ref="image" @change="updateImagePreview">
        <q-btn label="Upload Logo" size="sm" color="primary" icon="upload" class="mt-2 mr-2" type="button" @click.prevent="handleBtnUpload"></q-btn>
       <div class="text-xs text-red q-my-md" v-if="errors.logo"> {{ errors.logo[0]}}</div>
      </div>
      <q-list v-if="imagePreview">
       <q-item>
        <q-item-section top>
          <img :src="imagePreview" class="shadow-4 q-pa-xs bg-grey" style="width:100px;height:100px;object-fit:contain;"/>
        </q-item-section>
        <q-space />
        <q-item-section side>
          <div class="text-grey-8 q-gutter-xs">
            <q-btn @click="removeLogo" size="sm" round icon="delete" glossy color="red"/>
          </div>
          </q-item-section>
        </q-item>
      </q-list>       
      
    </div>
    <q-footer class="q-pa-sm bg-white">
        <q-btn :loading="isLoading" class="full-width" @click="save" label="Simpan Data" color="primary">
           <q-tooltip class="bg-accent">Simpan Data</q-tooltip>
        </q-btn>
    </q-footer>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data () {
    return {
      provinceOptions: [],
      subdistrictOptions: [],
      cityOptions: [],
      toko: null,
      isLoading: false,
      form: {
        name: '',
        phone: '',
        address: '',
        description: '',
        logo: '',
        is_remove_logo: false,
      },
      imagePreview: ''
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    },
    dataToko() {
      return this.$store.state.shop
    },
    errors () {
      return this.$store.state.errors
    }
  },
  methods: {
    save() {
      this.isLoading = true;
      let self = this;
      let formData = new FormData();

      for( const i in this.form) {
        if(this.form[i] && this.form[i] != null && this.form[i] != 'null') {
          formData.append(i, this.form[i])
        }
      }
      
      Api().post('shop',formData,{headers: {'Content-Type': 'multipart/form-data' }}).then(response => {
        self.isLoading = false
        if(response.status == 200) {
          self.$store.commit('SET_SHOP', response.data.results)
          // localStorage.setItem('_washop', JSON.stringify(response.data.results))
          this.$q.notify({
            type: 'positive',
            message: 'Berhasil menyimpan data'
          })
          self.$router.push({ name: 'Settings'})
        }
      }).catch(err => {
        self.isLoading = false
         this.$q.notify({
           type: 'negative',
            message: 'Gagal menyimpan data, Coba refresh halaman'
          })
      })

    },
    handleBtnUpload() {
      this.$refs.image.click()
    },
    updateImagePreview() {
        this.form.logo = this.$refs.image.files[0]

        const reader = new FileReader();

        reader.onload = (e) => {
            this.imagePreview = e.target.result;
        };

        reader.readAsDataURL(this.$refs.image.files[0]);
    },
    removeLogo() {
      if(this.toko.logo_path) {
        this.form.is_remove_logo = true
      }
      this.form.logo = ''
      this.imagePreview = ''
    },
    setDataToko() {
      this.form.name = this.toko.name ? this.toko.name : ''
      this.form.phone = this.toko.phone ? this.toko.phone : ''
      this.form.address = this.toko.address ? this.toko.address : ''
      this.form.description = this.toko.description ? this.toko.description : ''
      this.imagePreview = this.toko.logo ? this.toko.logo : ''
    },

  },
  mounted() {
    if(this.dataToko) {
      this.toko = this.dataToko
      this.setDataToko()
    } else {
      let self = this
      Api().get('shop').then(response => {
        if(response.status == 200) {
          // localStorage.setItem('_washop', JSON.stringify(response.data.results))
          self.toko = response.data.results
          self.setDataToko()
          self.$store.commit('SET_SHOP', response.data.results)
        }
      })
    }
  }
}
</script>

<style>

</style>