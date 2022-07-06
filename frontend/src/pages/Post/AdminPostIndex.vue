<template>
  <q-page :class="{'flex flex-center' : !posts.available}">
     <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List Post
        </q-toolbar-title>
        <q-btn class="gt-xs" no-caps flat icon="add_circle" :to="{name: 'PostCreate'}" label="Tambah Post"/>
      </q-toolbar>
    </q-header>
    <template  v-if="posts.available">
     <div class="q-pt-sm q-pb-xl">
      <q-list separator>
       <q-item v-for="post in posts.data" :key="post.id">

         <q-item-section avatar top class="q-pr-sm">
           <q-img v-if="post.image" :src="post.image_url" class="bg-white img-product-admin rounded-corners" ratio="1"/>
        </q-item-section>

        <q-item-section top> 
          <q-item-label class="ellipsis text-md">{{ post.title }}</q-item-label>
          <q-item-label caption class="ellipsis-2-lines">
            <div v-html="post.body"></div>
          </q-item-label>
          <div class="q-mt-xs">
            <q-chip dense v-if="post.is_listing" label="Listing" size="sm" color="accent" text-color="white" icon="check_circle"></q-chip>
            <q-chip dense v-if="post.is_promote" label="Promote" size="sm" color="teal" text-color="white" icon="check_circle"></q-chip>
          </div>
          <div class="q-mt-sm text-xs" v-if="post.category">
           <q-chip dense size="sm">
            <q-avatar icon="sell" color="primary" text-color="white"/>
           {{ post.category }}
          </q-chip>
          </div>
        </q-item-section>

        <q-item-section side top>
          <div class="text-grey-8 column q-gutter-y-sm">
            <q-btn @click="remove(post.id)" size="sm" round icon="delete" glossy color="red"/>
            <q-btn :to="{ name: 'PostEdit', params: {id: post.id }}" size="sm" round glossy color="info" icon="edit" />
          </div>
        </q-item-section>
      </q-item>
    </q-list>
    </div>
    </template>
    <template v-else>
      <div>Tidak ada data</div>
    </template>

    <q-inner-loading :showing="!posts.ready">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    
    <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
      <q-btn fab icon="add" color="primary" :to="{name: 'PostCreate'}" glossy/>
    </q-page-sticky>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'PostAdminIndex',
  data () {
    return {
      isSelect: '',
    }
  },
  computed: {
    ...mapState({
      posts: state => state.post.posts
    }),
  },
  methods: {
    ...mapActions('post', ['getAllPost', 'deletePost']),
    money(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number)
    },
    handleSelectFilter(str) {
      this.isSelect = str
      this.$store.commit('post/FILTERBY', str)
    },
    remove(id) {
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.deletePost(id)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
  },
  created() {
    if(!this.posts.data.length) this.getAllPost()
  }
}
</script>
