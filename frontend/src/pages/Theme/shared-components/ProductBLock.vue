<template>
  <div id="product" v-if="products.data.length">
    <template v-for="item in products.data">
      <div :key="item.id" v-if="item.items.length" class="block-container bg-linear">
        <div class="auto-padding">
          <div class="row items-end justify-between">
            <div class="block-title"><h2>{{ item.title }}</h2></div>
            <q-btn flat no-caps color="primary" padding="4px" :to="{name: 'ProductCategory', params:{ id: item.category_id }}">
              <span>Lihat Semua</span>
              <q-icon name="arrow_forward" size="16px"></q-icon>
            </q-btn>
          </div> 
          <div v-if="item.description" class="block-subtitle">{{ item.description }}</div>
          <div class="banner" v-if="item.banner_src">
            <router-link :to="{name: 'ProductCategory', params:{ id: item.category_id }}">
            <img :src="item.banner_src" :alt="item.title" class="cursor-pointer" />
              </router-link>
            </div>
          </div>
          <div class="block-content">
            <div v-if="config && config.home_view_mode == 'list'">
              <product-list-section :ready="products.ready" :products="item" />
            </div>
            <div v-else class="auto-padding-side">
              <swiper-product :products="item" />
            </div>
          </div>
      </div>
    </template>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import SwiperProduct from 'components/GlideProduct.vue'
import ProductListSection from 'components/ProductListSectionHome.vue'
export default {
  props: ['products'],
  components: { SwiperProduct, ProductListSection },
  computed: {
    ...mapState({
      config: state => state.config
    })
  }
}
</script>

<style>

</style>