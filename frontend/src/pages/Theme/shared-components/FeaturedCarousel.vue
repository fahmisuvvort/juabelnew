<template>
  <div class="overflow-hidden">
    <vue-glide :options="carouselOptions">
      <vue-glide-slide v-for="feature in datas" :key="feature.id">
        <div class="column col items-center text-center q-gutter-y-xs featured cursor-pointer" @click="showPost(feature)">
          <img v-if="feature.image" :src="feature.image_url" alt="" width="60" height="60" style="object-fit:contain;"/>
          <div class="text-sm text-weight-medium">{{ feature.label }}</div>
          <div v-if="feature.description" class="text-grey-7 text-auto description">{{ feature.description }}</div>
        </div>
      </vue-glide-slide>
    </vue-glide>
  </div>
</template>

<script>
export default {
  name: 'CategoryCarousel',
  props: {
    datas: Array
  },
  data () {
    return {
      carouselOptions: {
        rewind: false,
        perView: 3,
        gap: 5,
        bound: true
      }
    }
  },
  methods: {
    showPost(featured) {
      if(featured.post) {
        this.$router.push({ name: 'FrontPostShow', params: { slug: featured.post.slug }})
      }
    }
  }
}
</script>

<style>

</style>