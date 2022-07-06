<template>
  <q-page class="q-pt-md q-pb-xl" :class="{'flex flex-center' : !posts.available}">
    <q-header class="text-primary bg-white">
       <q-toolbar>
         <q-btn :to="{name: 'Home'}"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">Posts</q-toolbar-title>
       </q-toolbar>
    </q-header>
    <template v-if="posts.ready">
      <template v-if="posts.available">
        <post-list v-for="(post, index) in posts.data" :key="index" v-bind="post" />
      </template>
      <template v-else>
        <div>Tdak ada data</div>
      </template>
    </template>
    <template v-else>
    <post-list-skeleton v-for="i in 4" :key="i"/>
    </template>
  </q-page>
</template>

<script>
import PostList from 'components/PostList.vue'
import PostListSkeleton from 'components/PostListSkeleton.vue'
import { mapState } from 'vuex'
export default {
  components: { PostList, PostListSkeleton },
  computed: {
    ...mapState({
      posts: state => state.post.listing_posts
    })
  },
  created() {
    if(!this.posts.data.length){
      this.$store.dispatch('post/listingPost')
    }
  },
  meta() {
    return {
      title: 'Artikel',
      meta: {
        ogTitle:  { property: 'og:title', content: 'Artikel'},
        ogImage:  { property: 'og:image', content: this.posts.data.length?  this.posts.data[0].image_url : ''},
      }
      
    }
  }
}
</script>

<style>

</style>