<template>
  <vue-glide :options="carouselOptions">
    <vue-glide-slide v-for="(feature, index) in datas" :key="index" class="text-center">
        <div class="column col items-center text-center q-gutter-y-xs featured">
          <img v-if="feature.image" :src="feature.image_url" alt="" width="60" height="60" style="object-fit:contain;"/>
          <div class="text-sm text-weight-medium">{{ feature.label }}</div>
          <div v-if="feature.description" class="text-grey-7 text-auto description">{{ feature.description }}</div>
        </div>
    </vue-glide-slide>
  </vue-glide>
</template>

<script>
import { Glide, GlideSlide } from 'vue-glide-js'
import 'vue-glide-js/dist/vue-glide.css'

export default {
  name: 'GlideCarousel',
  props: {
    datas: Array
  },
  components: {
    'vue-glide' : Glide,
    'vue-glide-slide' : GlideSlide
  },
  data () {
    return {
      carouselOptions: {
        type: 'slider',
        gap: 3,
        perView: 3,
        animationDuration: 300,
        bound: true,
      },
    }
  },
  methods: {
    openCategory(id) {
      if(id) {
        this.$router.push({name: 'ProductCategory', params: {id:id}})
      }
    }
  }

}
</script>

<style>

</style>