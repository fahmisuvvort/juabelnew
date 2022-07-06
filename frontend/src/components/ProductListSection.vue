<template>
  <section>
    <div class="col-12 q-my-sm row justify-between" v-if="title">
      <div class="block-title">
        <h2 class="text-capitalize">{{ title }}</h2>
      </div>
      <q-btn class="text-xs text-primary" flat no-caps icon-right="chevron_right" label="more" v-if="path" @click="detail"></q-btn>
    </div>
    <div class="column q-gutter-y-sm">
      <template v-if="ready">
        <product-list v-for="(product, index) in products" :key="index" :product="product" />
      </template>
      <template v-else>
        <product-list-skeleton />
      </template>
    </div>
  </section>
</template>

<script>
import ProductList from 'components/ProductList.vue'
import ProductListSkeleton from 'components/ProductListSkeleton.vue'
export default {
  name: 'ProductList',
  components: { ProductList, ProductListSkeleton },
  props: {
    products: Array,
    title: String,
    path: {
      type: String,
      default: ''
    },
    query: {
      type: String,
      default: ''
    },
    ready: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    detail() {
      if(this.query) {
        this.$router.push({name: this.path, query: {q: this.query}})
      } else {
        this.$router.push({name: this.path})
      }
    }
  }
  

}
</script>

<style>

</style>