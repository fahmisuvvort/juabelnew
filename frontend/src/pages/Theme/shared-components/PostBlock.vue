<template>
  <div id="post" v-if="posts.length" class="block-container">
    <div class="block-heading">
      <div class="block-title auto-padding-side"><h2>Post Terbaru</h2></div>
    </div>
    <div class="block-content">
      <post-list v-for="(post, index) in posts" :key="index" v-bind="post" />
    </div>
    <div class="block-footer flex justify-center auto-padding">
      <q-btn label="Selengkapnya" icon-right="trending_flat" color="primary" :to="{name: 'FrontPostIndex'}"></q-btn>
    </div>
  </div>
</template>

<script>
import PostList from 'components/PostList.vue'
export default {
  props: ['posts'],
  components: { PostList }
}
</script>

<style>

</style>