<template>
  <q-page class="q-pb-xl bg-grey-1">
    <q-header class="text-primary bg-white box-shadow">
       <q-toolbar>
         <q-btn :to="{name: 'Home'}"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">{{ title }}</q-toolbar-title>
          <shopping-cart  />
       </q-toolbar>
    </q-header>
   <product-section :title="title" :products="products"></product-section>
    <div class="flex justify-center q-py-lg" v-if="products && products.links">
     <q-btn label="loadmore" color="primary" outline :loading="isLoadmore" v-if="products.links.next" @click="paginate(products.links.next)"></q-btn>
   </div>
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
import ProductSection from 'components/ProductSection.vue'
import ShoppingCart from 'components/ShoppingCart.vue'

import { Api } from 'boot/axios'
export default {
  name: 'ProductIndex',
  components: { ProductSection, ShoppingCart },
  data() {
    return {
      title: 'Katalog Produk',
      description: this.$store.state.meta.description,
      isLoadmore: false,
      isFilter: true
    }
  },
  computed: {
    products() {
      return this.$store.state.product.products
    },
  },
  methods: {
    ...mapActions('product', ['getProducts']),
    paginate(url) {
      this.isLoadmore = true
      Api().get(url).then(response => {
        if(response.status == 200) {
          this.$store.commit('product/SET_PAGINATE', response.data)
        }
      }).finally(() =>  this.isLoadmore = false)
    }
  },
  created() {
    if(this.$route.query.q){
      this.title = 'Produk ' + this.$route.query.q
    }
    if(!this.products.data.length) {
      this.getProducts()
    }
  },
  meta() {
    return {
      title: 'Katalog Produk',
      meta: {
        description: { name: 'description', content: this.description },
        ogDescription: { name: 'og:description', content: this.description },
        ogTitle:  { name: 'og:title', content: this.title },
        ogUrl:  { name: 'og:url', content: location.href },
      }
      
    }
  }
}
</script>
<style lang="scss">
.relative {
  position:relative;
}
.absolute {
  position:absolute;
  &__top-right{
    top:0;
    right:0;
  }
}
.mini .q-field__marginal,
.mini .q-field__control{
 height: 30px;
}
</style>