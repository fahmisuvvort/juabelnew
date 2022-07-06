<template>
  <q-page >
   <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List Kategori
        </q-toolbar-title>
         <q-btn class="gt-xs" flat label="Tambah Kategori" no-caps icon="add_circle" @click="addButton"/>
      <!-- <q-btn class="gt-xs" flat label="Tambah Kategori" no-caps icon="add_circle" :to="{name: 'CategoryForm'}"/> -->
      </q-toolbar>
    </q-header>
    <!-- <div class="">
      <q-list separator>
        <q-expansion-item
        v-for="item in categories.data" :key="item.id"
        header-class="text-weight-bold"
        :label="item.title"
        group="category"
        >
         <q-list separator>
            <q-item>
              <q-item-section avatar>
              Ikon
              </q-item-section>
              <q-item-section>
                Label
              </q-item-section>
              <q-item-section>
                Urutan
              </q-item-section>
              <q-item-section>
                Listing
              </q-item-section>
              <q-item-section side>
                Aksi
              </q-item-section>
            </q-item>
            <q-item v-for="cat in item.subcategory" :key="cat.id">
              <q-item-section avatar>
                <q-img :src="cat.src" ratio="1"/>
              </q-item-section>
              <q-item-section>
                <q-item-label>{{ cat.title }}</q-item-label>
              </q-item-section>
              <q-item-section >
                <div>{{ cat.weight }}</div>
              </q-item-section>
              <q-item-section>
                <div>
                <q-chip size="sm" :color="cat.is_front? 'positive' : 'grey'" text-color="white" icon="check_circle">
                  {{ cat.is_front? 'Yes' : 'No'}}
                </q-chip>
                </div>
              </q-item-section>
            
              <q-item-section side>
                <div class="text-grey-8 q-gutter-xs column">
                  <q-btn @click="remove(cat.id)" size="sm" round icon="delete" glossy color="red"/>
                  <q-btn :to="{ name: 'CategoryForm', query: {edit: cat.id }}" size="sm" round icon="edit" glossy color="info"/>
                </div>
              </q-item-section>
            </q-item>
          </q-list>
        </q-expansion-item>

      </q-list>
    </div> -->

    <div>
       <template v-if="categories.data.length">
       <category-block 
        editable
        ref="refCategory"
        @onRemove="onRemoveCategory"
        @onClear="onClearSelectableCategory" 
        :categories="categories.data" 
        @onEdit="onEditButton"/>

      </template>
    </div>

    <q-inner-loading :showing="!categories.ready">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
       <q-btn class="gt-xs" flat label="Tambah Kategori" no-caps icon="add_circle" @click="addButton"/>
    </q-page-sticky>
     <q-dialog v-model="modalShow">
      <q-card style="max-width:500px;width:90%;max-height:100%;">
        <div v-if="isOpenSelect">
          <div class="bg-white relative">
            <div style="max-height:800px;overflow:auto;" class="scroll q-pa-md">
              <category-block 
                ref="category"
                selectable
                @onClose="onCloseSelectableCategory"
                @onSelect="onSelectCategory"
                :categories="categories.data" 
                />
            </div>
          </div>
        </div>
        <div v-if="!isOpenSelect" class="q-pa-md">
          <q-input @click="openCategory" v-if="!categorySelected && categories.data.length" readonly label="Pilih parent Category">
            <template v-slot:append>
              <q-btn @click="openCategory" flat dense round icon="edit" />
            </template>
          </q-input>
          <q-input v-if="categorySelected" readonly :value="categorySelected.title" label="Parent Category">
            <template v-slot:append>
              <q-btn @click="clearCategorySelected" flat dense round icon="close" />
            </template>
          </q-input>
          <q-input  
          label="Nama Kategori" 
          v-model="form.title" required></q-input>
          <q-input mask="####" label="Urutan" v-model="form.weight" required/>
          <q-input type="textarea" rows="3" label="Deskripsi" v-model="form.description" />
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
              
            <div class="text-xs text-red q-my-md" v-if="errors && errors.images"> {{ errors.images[0]}}</div>
            </div>
            <div>
              <input type="file" class="hidden" ref="image" @change="updateImagePreview">
              <input type="file" class="hidden" ref="banner" @change="updateBannerPreview">
              <div class="q-my-lg" v-if="imagePreview">
                <div class="text-weight-bold text-md">Ikon</div>
                  <q-list bordered>
                    <q-item>
                      <q-item-section>
                        <img :src="imagePreview" class="q-pa-xs bg-white" style="width:80px;height:auto;object-fit:contain;"/>
                      </q-item-section>
                      <q-space />
                      <q-item-section side>
                          <q-btn @click="removeImage" size="sm" color="red" glossy round icon="delete" />
                      </q-item-section>
                    </q-item>
                  </q-list>      
                </div>
            </div>

            <div class="q-my-lg" v-if="bannerPreview">
              <div class="text-weight-bold text-md">Banner</div>
                <q-list bordered>
                  <q-item>
                    <q-item-section>
                      <img :src="bannerPreview" class="q-pa-xs bg-white" style="width:100%;height:100px;object-fit:contain;"/>
                    </q-item-section>
                    <q-space />
                    <q-item-section side>
                        <q-btn @click="removeBanner" size="sm" color="red" glossy round icon="delete" />
                    </q-item-section>
                  </q-item>
                </q-list>           
            </div> 

            </template>
          <q-card-actions class="justify-end sticky-bottom bg-white q-mt-md">
            <q-btn :disabled="loading" @click="closeModal" flat label="Batal" color="dark"></q-btn>
            <q-btn :loading="loading" @click="submit" flat label="Simpan" color="primary"></q-btn>
          </q-card-actions>
        </div>
      </q-card>
    </q-dialog>
     <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
      <q-btn glossy fab icon="add" color="primary" @click="addButton"/>
    </q-page-sticky>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import CategoryBlock from 'components/CategoryBlock.vue'
export default {
  components: { CategoryBlock },
  name: 'Categories',
  data () {
    return {
      isOpenSelect: false,
      modalShow: false,
      modal: false,
      removeId: null,
      selectedCategory: null,
      categorySelected: null,
      imagePreview: '',
      bannerPreview: '',
      images: '',
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
    }
  },
  computed: {
    ...mapState({
      categories: state => state.category.categoryAll,
      errors: state => state.errors,
      loading: state => state.loading
    }),
  },
  methods: {
     ...mapActions('category',['categoryStore', 'getCategories', 'getCategory', 'categoryUpdate', 'getAllCategories', 'categoryDelete']),
    remove (id) {
      this.removeId = id
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.categoryDelete(this.removeId).then(() => {
          this.getAllCategories()
          this.clearCategorySelected()
        })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
    selectNewImage () {
      this.$refs.image.click()
    },
    selectNewBanner () {
      this.$refs.banner.click()
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
    openCategory() {
      this.isOpenSelect = true
    },
    onSelectCategory(cat) {
      this.form.category_id = cat.id
      this.categorySelected = cat
      this.isOpenSelect = false
    },
    onCloseSelectableCategory() {
      this.isOpenSelect = false
    },
    clearCategorySelected() {
      this.isOpenSelect = false
      this.categorySelected = null
      this.form.category_id = ''

    },
    addButton() {
      this.clearForm()
      this.modalShow = true
      this.form._method = 'POST'
      this.formType = 'Add'
      this.form.weight = this.categories.data.length+1
    },
    onEditButton({category, parent}) {
      this.clearForm()
      this.formType = 'Edit'
      this.form._method = 'PUT'
      this.form.id = category.id
      this.form.title = category.title
      this.form.weight = category.weight
      this.form.description = category.description ?  category.description : ''
      this.form.category_id = category.category_id
      this.form.is_front = category.is_front
      this.imagePreview = category.src ? category.src : ''

      this.categorySelected = parent
      this.modalShow = true
    },
    closeModal() {
      this.clearCategorySelected()
      this.modalShow = false
      this.clearForm()
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
    onClearSelectableCategory() {
      console.log('clearable');
    },
    onRemoveCategory(id){
      this.clearCategorySelected()
      this.removeId = id
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.removeCategory()
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
    removeCategory () {
      this.categoryDelete(this.removeId).then(() => {
        this.REMOVE_CATEGORY(this.removeId)
        //  setTimeout(() => {
        //       this.$refs.refCategory.getRootCategory()
        //     },500)
      })
    },
    submit () {
      this.$store.commit('SET_LOADING', true)
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
        this.categoryStore(formData).then(() => {
          this.getAllCategories()
          this.clearCategorySelected()
          this.modalShow = false
        }).finally(() => this.$store.commit('SET_LOADING', false))
      }
      if(this.formType == 'Edit') {
        formData.append('_method', 'PUT')
        this.categoryUpdate({id: this.form.id, data: formData}).then(() => {
          this.getAllCategories()
          this.clearCategorySelected()
          this.modalShow = false
        }).finally(() => this.$store.commit('SET_LOADING', false))
      }
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
  },
  created () {
    if(!this.categories.data.length) this.getAllCategories()
  }
}
</script>
