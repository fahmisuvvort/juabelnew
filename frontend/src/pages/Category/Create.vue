<template>
  <q-page class="">
    <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Tambah Kategori
        </q-toolbar-title>
        
      </q-toolbar>
    </q-header>
    <div class="q-pa-md q-gutter-y-md">
      <q-input filled label="Title" v-model="form.title"></q-input>
      <q-input filled type="number" label="Urutan" v-model="form.weight"
      :rules="[val => val && val > 0 || 'Data tidak boleh kosong']"
      ></q-input>
       <div class="text-xs text-red q-my-md" v-if="errors.title"> {{ errors.title[0]}}</div>
       <div class="border rounded q-pa-sm">
        <div>
          <q-checkbox v-model="form.is_front" label="Tampilkan produk di beranda"></q-checkbox>
          <div class="text-xs text-grey-6 q-pa-xs">
            Pilihan apakah produk dibawah kategori ini akan di listing di halaman beranda
          </div>
        </div>
       </div>
      <div>

      <div class="row items-center justify-between">
        <q-btn label="Upload Ikon" size="sm" color="primary" icon="upload" class="mt-2 mr-2" type="button" @click.prevent="selectNewImage" />
        <q-btn label="Upload Banner" size="sm" color="teal" icon="upload" class="mt-2 mr-2" type="button" @click.prevent="selectNewBanner" />
      </div>
        
       <div class="text-xs text-red q-my-md" v-if="errors.images"> {{ errors.images[0]}}</div>
      </div>
      <div>
        <input type="file" class="hidden" ref="image" @change="updateImagePreview">
        <input type="file" class="hidden" ref="banner" @change="updateBannerPreview">
        <div class="my-lg" v-if="imagePreview">
          <div class="text-weight-bold text-md">Ikon</div>
            <q-list bordered>
              <q-item>
                <q-item-section>
                  <img :src="imagePreview" class="shadow-4 q-pa-xs bg-white" style="width:100px;auto;object-fit:contain;"/>
                </q-item-section>
                <q-space />
                <q-item-section side>
                    <q-btn @click="removeImage" size="sm" color="red" glossy round icon="delete" />
                </q-item-section>
              </q-item>
            </q-list>      
          </div>
      </div>
      <div class="my-lg" v-if="bannerPreview">
        <div class="text-weight-bold text-md">Banner</div>
          <q-list bordered>
            <q-item>
              <q-item-section>
                <img :src="bannerPreview" class="shadow-4 q-pa-xs bg-white" style="width:200px;height:auto;object-fit:contain;"/>
              </q-item-section>
              <q-space />
              <q-item-section side>
                  <q-btn @click="removeBanner" size="sm" color="red" glossy round icon="delete" />
              </q-item-section>
            </q-item>
          </q-list>           
        </div>     
    </div>
    <q-footer>
       <q-btn :loading="loading" class="full-width" @click="submit" label="Simpan Data">
           <q-tooltip class="bg-accent">Simpan Data</q-tooltip>
        </q-btn>
    </q-footer>
  </q-page>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
  data () {
    return {
      form: {
        images: '',
        title: '',
        is_front: true,
        is_special: false,
        weight: 1,
        banner: ''
      },
      modal: false,
      imagePreview: '',
      bannerPreview: ''
    }
  },
  computed: {
     ...mapState({
      loading: state => state.loading,
      errors: state => state.errors
    }),
  },
  methods: {
    ...mapActions('category',['categoryStore', 'getCategories']),
    submit () {
      let formData = new FormData();
      formData.append('title' , this.form.title)
      formData.append('images', this.form.images);
      formData.append('banner', this.form.banner);
      formData.append('weight', this.form.weight);
      formData.append('is_front', this.form.is_front ? 1 : 0);
      formData.append('is_special', this.form.is_special ? 1 : 0);

      this.categoryStore(formData)
    },
    selectNewImage () {
      this.$refs.image.click()
    },
    selectNewBanner () {
      this.$refs.banner.click()
    },
    updateImagePreview() {

      let file = this.$refs.image.files[0]
       if(!file) return

        this.form.images = file

        const reader = new FileReader();

        reader.onload = (e) => {
            this.imagePreview = e.target.result;
        };

        reader.readAsDataURL(file);
        
    },
    updateBannerPreview() {
       let file = this.$refs.banner.files[0]
       if(!file) return

        this.form.banner = file

        const reader = new FileReader();

        reader.onload = (e) => {
            this.bannerPreview = e.target.result;
        };

        reader.readAsDataURL(file);
        
    },
    removeImage() {
      this.imagePreview = ''
      this.form.images = ''
    },
    removeBanner() {
      this.bannerPreview = ''
      this.form.banner = ''
    }
  }
}
</script>

<style>

</style>