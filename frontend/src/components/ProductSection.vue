<template>
  <section>
    <div class="row q-px-sm q-pt-md q-pb-sm justify-between items-center" v-if="products.ready && products.available">
      <div>
        <q-btn @click="handleMenuCategory" outline color="grey-7" padding="3px 10px" no-caps>
          <q-icon name="apps" size="15px"></q-icon>
          <span>Kategori</span>
        </q-btn>
      </div>
      <div class="q-gutter-x-xs" v-if="config">
        <q-btn @click="changeViewMode('grid')" :outline="config.product_view_mode != 'grid'" unelevated size="12px" :color="config.product_view_mode == 'grid' ? 'secondary' : 'grey-7'" dense icon="grid_view"></q-btn>
        <q-btn @click="changeViewMode('list')" :outline="config.product_view_mode != 'list'" unelevated size="12px" :color="config.product_view_mode == 'list' ? 'secondary' : 'grey-7'" dense icon="view_list"></q-btn>
      </div>
    </div>
    <div class="column q-gutter-y-sm" v-if="config && config.product_view_mode == 'list'">
      <template v-if="products.ready">
        <product-list v-for="(product, index) in products.data" :key="index" :product="product" />
      </template>
      <template v-else>
        <product-list-skeleton v-for="a in 4" :key="a" />
      </template>
    </div>
     <div class="row items-stretch q-px-xs" v-else>
      <template v-if="products.ready">
        <product-card v-for="(product, index) in products.data" :key="index" :product="product" />
      </template>
      <template v-else>
        <product-card-skeleton v-for="a in 4" :key="a" />
      </template>
    </div>
     <q-dialog
     v-model="isMenuCategory"
      position="bottom"
      >
        <category-menu />
      </q-dialog>  
  </section>
</template>

<script>
import ProductCard from 'components/ProductCard.vue'
import ProductCardSkeleton from 'components/ProductCardSkeleton.vue'
import ProductList from 'components/ProductList.vue'
import ProductListSkeleton from 'components/ProductListSkeleton.vue'
import CategoryMenu from 'components/CategoryMenu.vue'

export default {
  name: 'ProductSection',
  components: { 
    ProductCard, 
    ProductList, 
    ProductCardSkeleton, 
    ProductListSkeleton ,
    CategoryMenu
    },
  props: {
    products: Object,
    title: String,
  },
  data() {
    return {
      viewMode: 'grid',
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    },
    isMenuCategory: {
      get() {
        return this.$store.state.isMenuCategory
      },
      set(status){
        this.$store.commit('SET_MENU_CATEGORY', status)
      }
    }
  },
  methods: {
    changeViewMode(str) {
      this.$store.commit('SET_PRODUCT_VIEW_MODE', str)
    },
    handleMenuCategory() {
      this.isMenuCategory = !this.isMenuCategory
    }
    
  }
  

}
</script>
