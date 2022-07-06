<template>
  <q-dialog v-model="prompt" persistent>
    <q-card style="min-width: 350px">

      <q-card-section class="q-pt-none">
        <q-input dense v-model="search" autofocus @keyup.enter="searchNow = false" placeholder="ketik sesuatu.."/>
      </q-card-section>

      <q-card-actions align="right" class="text-primary">
        <q-btn flat label="Cancel" v-close-popup />
        <q-btn flat label="Add address" @click="searchNow" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    onsearch: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return{
      search:'',
      prompt: this.onsearch
    }
  },
  methods: {
    searchNow() {
      if(this.search || this.search != '') {
        this.$store.dispatch('product/searchProducts', this.search)
        this.$router.push({name: 'ProductSearch'})
      }
    }
  }
}
</script>

<style>

</style>