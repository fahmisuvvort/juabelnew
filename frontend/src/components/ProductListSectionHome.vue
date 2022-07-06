<template>
  <section>
    <div class="column q-gutter-y-sm">
      <template v-if="ready">
        <product-list v-for="(product, index) in products.items" :key="index" :product="product" />
      </template>
      <template v-else>
        <product-list-skeleton />
      </template>
    </div>
    <div class="q-my-lg q-py-lg flex justify-center">
      <q-btn unelevated label="Selengkapnya" icon-right="trending_flat" color="primary" :to="{name: 'ProductCategory', params:{ id: products.category_id }}"></q-btn>
    </div>
  </section>
</template>

<script>
import ProductList from 'components/ProductList.vue'
import ProductListSkeleton from 'components/ProductListSkeleton.vue'
export default {
  name: 'ProductListHome',
  components: { ProductList, ProductListSkeleton },
  props: {
    products: Object,
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