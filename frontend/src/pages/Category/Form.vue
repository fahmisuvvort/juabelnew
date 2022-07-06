<template>
  <q-page class="">
    <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         {{ formType }} Kategori
        </q-toolbar-title>
        
      </q-toolbar>
    </q-header>
    <div class="q-pa-md q-gutter-y-md">
      <div @click="parentModal = true" class="cursor-pointer q-pa-md full-width border q-filled  text-grey-8" label="Parent Kategori">{{ form.category_id? parentCategoryTitle : 'Pilih Parent Kategori'  }}</div>
      <q-input filled label="Title" v-model="form.title" :rules="[val => val && val.length > 0 || 'Wajib diisi']" />
      <q-input filled autogrow label="Deskripsi" v-model="form.description" />
      <q-input filled mask="####" label="Urutan" v-model="form.weight"
      :rules="[val => val && val.length > 0 || 'Wajib diisi']"
      />
      <template v-if="!form.category_id">
       <div class="border rounded q-pa-sm">
        <div>
          <q-toggle v-model="form.is_front" label="Tampilkan produk di beranda"></q-toggle>
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
                  <img :src="imagePreview" class="shadow-4 q-pa-xs bg-white" style="width:80px;height:auto;object-fit:contain;"/>
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

      </template>
    </div>
    <q-dialog v-model="parentModal">
      <q-card class="card-medium">
        <div class="card-heading">Kategori</div>
        <div class="bg-white relative">
          <div class="q-pa-sm">
            <category-block 
              ref="category"
              selectable
              @onClose="onCloseSelectableCategory"
              @onSelect="onSelectCategory"
              :categories="categoryAll" 
              />
          </div>
        </div>
      </q-card>
    </q-dialog>
    <q-footer class="q-px-md q-py-sm bg-white">
       <q-btn :loading="loading" class="full-width" @click="submit" label="Simpan Data" color="primary">
           <q-tooltip class="bg-accent">Simpan Data</q-tooltip>
        </q-btn>
    </q-footer>
  </q-page>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import { Api } from 'boot/axios'
import CategoryBlock from 'components/CategoryBlock.vue'
export default {
  components: { CategoryBlock },
  data () {
    return {
      parentModal: false,
      category: this.$route.query.edit? this.$store.getters['category/getById'](parseInt(this.$route.query.edit)) : '',
      formType: 'Add',
      form: {
        id: '',
        images: '',
        title: '',
        description: '',
        category_id: '',
        is_front: true,
        weight: 1,
        banner: '',
        remove_banner: false
      },
      modal: false,
      imagePreview: '',
      bannerPreview: '',
      categoryAll: [],
      categorySelected: null,
      isOpenSelect: false
    }
  },
  computed: {
     ...mapState({
      loading: state => state.loading,
      errors: state => state.errors,
      categories: state => state.category.categories
    }),
    parentCategoryTitle() {
      if(this.categorySelected) {
        return this.categorySelected.title
      }
      return ''
    }
  },
  created() {
    if(!this.categories.data.length) {
      this.getCategories()
    }
    this.getCategryAll()
    this.clearForm()
    if(this.$route.query.edit) {
      this.formType = 'Edit'
      if(!this.category) {
        this.getCategory(this.$route.query.edit).then((response) => {
          this.category = response.data.results
          this.setData() 
        })
      }else {
        this.setData() 
      }
    } else {
      this.formType = 'Add'
      this.category = {}
      this.clearForm()
    }
  },
  methods: {
    ...mapActions('category',['categoryStore', 'getCategories', 'getCategory', 'categoryUpdate']),
    onCloseSelectableCategory() {
      this.isOpenSelect = false
    },
    clearCategorySelected() {
      this.isOpenSelect = false
      this.categorySelected = null
      this.form.category_id = ''

    },
    onSelectCategory(cat) {
      this.form.category_id = cat.id
      this.categorySelected = cat
      this.isOpenSelect = false
    },
    getCategryAll() {
      Api().get('category-all').then(response => {
        if(response.status == 200) {
          this.categoryAll = response.data.results
        }
      })
    },
    submit () {
      let formData = new FormData();
      formData.append('title' , this.form.title)
      formData.append('description' , this.form.description)

      if(!this.form.category_id) {
        formData.append('images', this.form.images);
        formData.append('banner', this.form.banner);
      }
      formData.append('weight', this.form.weight);
      formData.append('is_front', this.form.is_front);
      formData.append('category_id', this.form.category_id);

      if(this.formType == 'Edit') {
        formData.append('remove_banner', this.form.remove_banner);
      }

      if(this.formType == 'Add') {
        formData.append('_method', 'POST')
        this.categoryStore(formData)
      }
      if(this.formType == 'Edit') {
        formData.append('_method', 'PUT')
        this.categoryUpdate({id: this.form.id, data: formData})
      }
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
    setData() {
      this.form.id = this.category.id
      this.form.title = this.category.title
      this.form.description = this.category.description ? this.category.description : ''
      this.form.weight = this.category.weight
      this.form.is_front = this.category.is_front
      this.form.is_special = this.category.is_special
      this.imagePreview = this.category.src ? this.category.src : ''
      this.bannerPreview = this.category.banner_src ? this.category.banner_src : ''

    },
    clearForm() {
      this.form.id = ''
      this.form.title = ''
      this.form.description = ''
      this.form.weight =1,
      this.form.is_front = true
      this.form.is_special =  ''
      this.form.images = '',
      this.form.banner = '',
      this.form.remove_banner = false

      this.imagePreview = ''
      this.bannerPreview = ''
    },
    removeImage() {
      this.imagePreview = ''
      this.form.images = ''
    },
    removeBanner() {
      this.bannerPreview = ''
      this.form.banner = ''
      this.form.remove_banner = true
    }
  }
}
</script>
