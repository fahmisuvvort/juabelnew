<template>
  <q-page :class="{'flex flex-center':!products.available}" class="q-pb-xl bg-grey-1">
     <q-header class="text-primary bg-white box-shadow">
       <q-toolbar>
         <q-btn @click="backButton"
            flat round dense
            icon="arrow_back" />
          <q-toolbar-title class="text-weight-bold brand">{{ title }}</q-toolbar-title>
          <shopping-cart  />
       </q-toolbar>
    </q-header>
    <template v-if="products.available">
      <product-section title="Produk Katalog" :products="products"></product-section>
      <div class="flex justify-center q-py-lg" v-if="products.links">
        <q-btn label="loadmore" color="primary" outline :loading="isLoadmore" v-if="products.links.next" @click="paginate(products.links.next)"></q-btn>
      </div>
    </template>
    <template v-if="!products.available">
      <div class="column items-center">
        <p class="text-grey-8 text-weight-bold text-center">Tidak ada produk ditemukan</p>
        <q-btn unelevated :to="{name: 'ProductIndex'}" rounded text-color="white" color="primary"
            icon="keyboard_backspace" label="Kehalaman Produk" no-caps/>
      </div>
    </template>
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
import ProductSection from 'components/ProductSection.vue'
import ShoppingCart from 'components/ShoppingCart.vue'
import { Api } from 'boot/axios'
export default {
  name: 'ProductCategory',
  components: { ProductSection, ShoppingCart },
  data () {
    return {
      likes: [],
      description: this.$store.state.meta.description,
      shop: this.$store.state.shop,
      isLoadmore: false,
    }
  },
  computed: {
    favorites: function() {
      return this.$store.state.product.favorites
    },
    products() {
      return this.$store.state.product.productByCategory
    },
    categories() {
      return this.$store.state.category.categories
    },
    title() {
      return this.$store.state.meta.title
    }
      
  },
  methods: {
    ...mapActions('product', ['getProductsByCategory']),
    backButton() {
      if(window.history.length > 2) {
        window.history.back()
      }else {
        this.$router.push({name: 'ProductIndex'})
      }
    },
    getCategoryTitle() {
      if(this.categories.data.length) {
        let c = this.categories.data.find(el => el.id == this.$route.params.id)
        if(c != undefined) {
          this.$store.commit('SET_META_TITLE', c.title)
        }
      }
    },
    paginate(url) {
      this.isLoadmore = true
      Api().get(url).then(response => {
        if(response.status == 200) {
          this.$store.commit('product/SET_PRODUCT_CATEGORY_PAGINATE', response.data)
        }
      }).finally(() =>  this.isLoadmore = false)
    }
  },
  mounted() {
    this.getCategoryTitle()
  },
  // watch: {
  // "$route.params.id": {
  //     handler: function(value) {
  //       // console.log(this.$route.name);
  //       this.getProductsByCategory(value)
  //     },
  //     deep: true,
  //     immediate: true,
  //   },
  // },
  created() {
    if(this.products.data.length) {

      if(this.products.data[0].id != this.$route.params.id) {

        this.$store.commit('product/CLEAR_PRODUCT_CATEGORY')
        this.getProductsByCategory(this.$route.params.id)

      }

    } else {

      this.$store.commit('product/CLEAR_PRODUCT_CATEGORY')
      this.getProductsByCategory(this.$route.params.id)
    }
  },
  meta() {
  return {
    title: this.title,
    meta: {
        description: { name: 'description', content: this.description },
        ogDescription: { name: 'og:description', content: this.description },
        ogTitle:  { name: 'og:title', content: this.title },
        ogUrl:  { name: 'og:url', content: location.href },
        ogImage:  { name: 'og:image', content: this.shop?.logo ? this.shop.logo: ''},
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