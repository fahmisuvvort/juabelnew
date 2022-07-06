<template>
  <div class="overflow-hidden glide-product">
    <vue-glide :options="glideOptions">
      <vue-glide-slide v-for="product in products.items" :key="product.id" class="relative box-shadow">
        <swiper-product-card :product="product" />
      </vue-glide-slide>
      <vue-glide-slide class="relative ">
        <div class="full-height flex column relative text-center justify-center items-center">
          <div>
            <q-btn unelevated icon="navigate_next" round size="16px" color="primary" :to="{name: 'ProductCategory', params:{ id: products.category_id }}"></q-btn>
            <div class="q-pt-md">Selengkapnya <br>di {{ products.title }}</div>
          </div>
         </div>
      </vue-glide-slide>
    </vue-glide>  
  </div>
</template>

<script>
import SwiperProductCard from 'components/SwiperProductCard'
  export default {
    name: 'GlideProduct',
    props: {
      products: Object
    },
    components: { SwiperProductCard },
    data() {
      return {
        glideOptions: {
          rewind: false,
          perView: 2,
          gap: 8,
          bound: true,
          peek: {
           before: 0, 
           after: 50
          }
        }
      }
    },
    created() {
      if(window.innerWidth < 600) {
        this.glideOptions.peek.after = 40
      }
      if(window.innerWidth < 481) {
        this.glideOptions.gap = 5
        this.glideOptions.peek.after = 10
      }
      
      if(window.innerWidth < 300) {
        this.glideOptions.perView = 1
        this.glideOptions.gap = 4
        this.glideOptions.peek.after = 100
      }
    }
  }
</script>
