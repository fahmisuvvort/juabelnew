<template>
  <q-page class="bg-grey-1 default" :class="{'flex flex-center' : loading }">
    <q-header class="bg-white text-dark box-shadow">
      <q-toolbar class="items-center sans">
        <img v-if="shop" class="logo" :src="shop.logo? shop.logo : '/icon/logo.png'" />
        <q-toolbar-title v-if="shop && shop.name" class="text-weight-bold text-primary">{{ shop.name }}</q-toolbar-title>
        <shopping-cart  />
      </q-toolbar>
    </q-header>
    <template v-if="!loading">

      <div id="slider" class="q-pt-sm" v-if="sliders.data.length">
        <slider :datas="sliders.data" />
      </div>
      <div id="featured" class="auto-padding-side block-container q-pt-md" v-if="blocks.featured.length">
        <featured-carousel :datas="blocks.featured" />
      </div>
      <div id="categories" v-if="categories && categories.data.length > 1" class="auto-padding-side block-container">
        <div class="block-heading">
          <div class="block-title"><h2>Kategori</h2></div>
        </div>
        <div class="block-content q-pb-sm">
          <category-carousel :datas="categories.data" />
        </div>
      </div>

      <div id="product-promo" v-if="productPromo.length" >
        <product-promo :product_promo="productPromo" />
      </div>
      
      <div v-if="banner1" class="banner auto-padding-side block-container">
        <img :src="banner1.image_url" @click="goToPost(banner1)">
      </div>
      
      <product-block :products="products" />

      <div v-if="blocks.partner.length" class="partner auto-padding-side block-container">
        <div class="block-heading">
          <div class="block-title"><h2>Partners</h2></div>
        </div>
        <div class="block-content">
          <partner-carousel :datas="blocks.partner" />
        </div>
      </div>
      
      <div v-if="banner2" class="banner auto-padding-side block-container">
        <img :src="banner2.image_url" @click="goToPost(banner2)">
      </div>

      <post-block :posts="posts" />

      <div v-if="banner3" class="banner auto-padding block-container">
        <img :src="banner3.image_url" @click="goToPost(banner3)">
      </div>

      <install-app />

      <footer-block />
    </template>

      <q-inner-loading :showing="loading">
        <q-spinner-facebook size="50px" color="primary"/>
      </q-inner-loading>
  </q-page>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import ShoppingCart from 'components/ShoppingCart.vue'
import Slider from './block/Slider.vue'
import ProductBlock from './../shared-components/ProductBLock.vue'

export default {
  name: 'PageIndex',
  components: {
    ShoppingCart,
    Slider, 
    ProductBlock, 
    'category-carousel': () => import('./block/CategoryCarousel.vue'),
    'product-promo': () => import('./../shared-components/ProductPromo.vue'),
    'post-block': () => import('./../shared-components/PostBlock.vue'), 
    'featured-carousel': () => import('./../shared-components/FeaturedCarousel.vue'),
    'partner-carousel': () => import('components/PartnerCarousel.vue'),
    'post-block': () => import('./../shared-components/PostBlock.vue'), 
    'footer-block': () => import('./../shared-components/FooterBlock.vue'),
    'install-app': () => import('components/InstallApp.vue')
  },
  data() {
    return {
      viewMode: 'grid',
      search: '',
      slide: 0,
    }
  },
  computed: {
    ...mapState({
      blocks: state => state.block.blocks,
      sliders: state => state.slider.sliders ,
      categories: state => state.category.categories ,
      products: state => state.product.initial_products,
      shop: state => state.shop,
      loading: state => state.loading,
      posts: state => state.post.initialPost,
      config: state => state.config,
      productPromo: state => state.product.product_promo,
    }),
    cheight: function() {
      let n =(this.$q.screen.width /1.7)
      if(this.$q.screen.width > 600) {
        return 400 +'px'
      } else {
        return (this.$q.screen.width /1.5) +'px'
      }
    },
    banner1() {
      if(this.blocks.banner.length) {
        let banner = this.blocks.banner.find(b => b.weight == 1)
        if(banner != undefined) {
          return banner
        } else {null
          return 
        }
      }
      return null
    },
    banner2() {
      if(this.blocks.banner.length) {
        let banner = this.blocks.banner.find(b => b.weight == 2)
        if(banner != undefined) {
          return banner
        } else {null
          return 
        }
      }
      return null
    },
    banner3() {
      if(this.blocks.banner.length) {
        let banner = this.blocks.banner.find(b => b.weight == 3)
        if(banner != undefined) {
          return banner
        } else {null
          return 
        }
      }
      return null
    },
  },
  methods: {
    ...mapActions(['getInitialData']),
    showProductByCategory(id) {
      this.$router.push({name: 'ProductCategory', params: { id:id }})
    },
    searchNow() {
      if(!this.search || this.search == '') return
        this.$router.push({name: 'ProductSearch', query: {q: this.search }})
    },
    goToPost(block) {
      if(block.post) {
        this.$router.push({name: 'FrontPostShow', params: { slug: block.post.slug }})
      }
    }
  },
  mounted() {
    if(this.config) {
      this.viewMode = this.config.home_view_mode
    }
  },
created() {
  if(!this.shop || !this.products.data.length || this.$route.query.load) {
      this.getInitialData()
    }
  }
}
</script>
