<template>
  <q-page class="">
    <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Edit Kategori
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

        <q-btn label="Upload Gambar Kategori" size="sm" color="primary" icon="upload" class="mt-2 mr-2" type="button" @click.prevent="selectNewImage">
        
      </q-btn>
       <div class="text-xs text-red q-my-md" v-if="errors.images"> {{ errors.images[0]}}</div>
      </div>
      <div>
        <input type="file" class="hidden" ref="image" @change="updateImagePreview" multiple>
      <q-list v-if="imagePreview">
       <q-item>
        <q-item-section>
          <img :src="imagePreview" class="shadow-4 q-pa-xs bg-white" style="width:100px;height:70px;object-fit:cover;"/>
        </q-item-section>
        <q-space />
        <q-item-section side>
            <q-btn @click="removeImage" size="sm" color="red" glossy round icon="delete" />
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
      category: this.$store.getters['category/getById'](parseInt(this.$route.params.id)),
      form: {
        id: '',
        images: '',
        title: '',
        is_front: true,
        weight: 0,
        remone_banner: false
      },
      modal: false,
      imagePreview: ''
    }
  },
  computed: {
     ...mapState({
      loading: state => state.loading,
      errors: state => state.errors
    }),
  },
  methods: {
    ...mapActions('category',['categoryUpdate', 'getCategories', 'getCategory']),
    submit () {
      let formData = new FormData();
      formData.append('_method' , 'PUT')
      formData.append('title' , this.form.title)
      formData.append('images', this.form.images);
      formData.append('weight', this.form.weight);
      formData.append('is_front', this.form.is_front == true ? 1 : 0);

      this.categoryUpdate({id: this.form.id, data: formData })
    },
    selectNewImage () {
      this.$refs.image.click()
    },
    removeImage() {
      this.imagePreview = ''
    },
    updateImagePreview() {

        this.form.images = this.$refs.image.files[0]

        const reader = new FileReader();

        reader.onload = (e) => {
            this.imagePreview = e.target.result;
        };

        reader.readAsDataURL(this.$refs.image.files[0]);
        
    },
    setData() {
      this.form.id = this.category.id
      this.form.title = this.category.title
      this.form.weight = this.category.weight
      this.form.is_front = this.category.is_front == 1 ? true : false
      this.form.is_special = this.category.is_special == 1 ? true : false
      this.imagePreview = this.category.src
    }
  },
  created() {
    if(!this.category) {
      this.getCategory(this.$route.params.id).then((response) => {
        this.category = response.data.results
        this.setData() 
      })
    }else {
      this.setData() 
    }
  }
}
</script>

<style>

</style>