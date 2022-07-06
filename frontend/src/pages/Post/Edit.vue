<template>
  <q-page padding>
    <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Edit Post
        </q-toolbar-title>
        
      </q-toolbar>
    </q-header>
    <q-form @submit.prevent="submitPost">
      <q-input v-model="form.title" label="Title" :rules="[ val => val && val.length > 0 || 'Required']"></q-input>
      <q-input v-model="form.tags" label="Kategori"></q-input>
      <div class="row justify-between items-center text-grey-8 q-py-sm q-my-xs border-b">
        <div>Tampil di Beranda</div>
        <div>
           <q-toggle v-model="form.is_promote" :label="form.is_promote? 'ya' : 'tidak'" left-label></q-toggle>
        </div>
      </div>
      <div class="row justify-between items-center text-grey-8 q-py-sm q-my-xs border-b">
        <div>Tampil di Halaman</div>
        <div>
           <q-toggle v-model="form.is_listing" :label="form.is_listing? 'ya' : 'tidak'" left-label></q-toggle>
        </div>
      </div>
      <div class="q-mt-md">
        <div class="q-my-sm label-text">
          Konten
        </div>
        <q-editor v-model="form.body"></q-editor>
      </div>
      <q-footer>
        <q-btn label="Update Data" type="submit" color="primary" class="full-width"></q-btn>
      </q-footer>
    </q-form>
    <div class="q-my-md">
      <q-btn label="Upload Gambar" size="sm" color="primary" icon="upload" class="mt-2 mr-2" type="button" @click.prevent="selectNewImage" />
    </div>
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
    <input type="file" class="hidden" ref="image" @change="updateImagePreview" />
    <q-inner-loading :showing="loading">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'PostCreate',
  data() {
    return {
      loading: false,
      form: {
        id: '',
        title: '',
        tags: '',
        body: '',
        image: '',
        del_image: false,
        is_listing: false,
        is_promote: true
      },
      imagePreview: ''
    }
  },
  methods: {
    ...mapActions('post', ['updatePost', 'getSinglePost']),
    submitPost() {
      this.updatePost(this.form)
    },
    updateImagePreview() {
     this.form.image = this.$refs.image.files[0]
     if(!this.form.image) return

        const reader = new FileReader();

        reader.onload = (e) => {
            this.imagePreview = e.target.result;
        };

        reader.readAsDataURL(this.$refs.image.files[0]);
    },
    selectNewImage () {
      this.$refs.image.click()
    },
    removeImage() {
      this.imagePreview = ''
      this.form.image = ''
      this.form.del_image = true
    },
    getPostData() {
      this.loading = true
      this.getSinglePost(this.$route.params.id).then(response => {
        this.loading = false
        if(response.status == 200) {
          let data = response.data.results
          this.form.id = data.id
          this.form.title = data.title
          this.form.tags = data.tags
          this.form.is_promote = data.is_promote
          this.form.is_listing = data.is_listing
          this.form.body = data.body
          this.imagePreview = data.image_url
        }
      }).catch(() => {
        this.loading = false
      })

    }
  },
  created() {
    this.getPostData()
  }
}
</script>
