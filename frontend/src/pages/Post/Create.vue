<template>
  <q-page padding>
    <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Tambah Post
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
        <q-btn :loading="loading" label="Simpan Data" type="submit" color="primary" class="full-width"></q-btn>
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
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'PostCreate',
  data() {
    return {
      form: {
        title: '',
        tags: '',
        body: '',
        image: '',
        is_listing: true,
        is_promote: true
      },
      imagePreview: ''
    }
  },
  computed: {
    loading() {
      return this.$store.state.loading
    }
  },
  methods: {
    ...mapActions('post', ['addPost']),
    submitPost() {
      this.$store.commit('SET_LOADING', true)
      this.addPost(this.form)
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
    }
  }
}
</script>
