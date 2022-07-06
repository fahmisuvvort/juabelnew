<template>
 <q-page :class="{'flex flex-center':!products.available}" class="q-pb-xl bg-grey-1">
   <q-header class="text-primary bg-white box-shadow">
       <q-toolbar>
         <q-btn @click="backButton"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">Produk Favorite</q-toolbar-title>
          <shopping-cart  />
       </q-toolbar>
    </q-header>
    <template v-if="products.available">
      <product-section title="Produk Favorit" :products="products"></product-section>
    </template>
     <template v-if="!products.available">
      <div class="column items-center">
        <p class="text-grey-8 text-weight-bold text-center">Tidak ada produk favorit</p>
        <q-btn :to="{name: 'ProductIndex'}" rounded text-color="white" color="primary" unelevated
            icon="keyboard_backspace" label="Kehalaman produk" no-caps/>
      </div>
    </template>
  </q-page>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import ProductSection from 'components/ProductSection.vue'
import ShoppingCart from 'components/ShoppingCart.vue'
export default {
  name: 'ProductFavorite',
  components: {ProductSection, ShoppingCart },
  computed: {
    ...mapState({
      favorites: state => state.product.favorites,
      products: state => state.product.productFavorites
    }),
  },
  methods: {
    ...mapActions('product', ['getProductsFavorites']),
    backButton() {
      if(window.history.length > 2) {
        window.history.back()
      }else {
        this.$router.push({name: 'ProductIndex'})
      }
    },
  },
  created() {
    if(this.favorites.length) {
      this.$store.commit('product/CLEAR_PRODUCT_FAVORITE')
      this.getProductsFavorites({ pids: this.favorites })
    } else {
      this.$store.commit('product/SET_PRODUCT_FAVORITE_STATUS', { ready: true, available: false })
    }
  },
  meta() {
  return {
    title: 'Produk Favorit',
      meta: {
        ogTitle:  { name: 'og:title', content: 'Produk Favorit' },
        ogUrl:  { name: 'og:url', content: location.href },
        ogImage:  { name: 'og:image', content: this.shop?.logo ? this.shop.logo : '' },
      }
      
    }
  }
}
</script>

<style>

</style>