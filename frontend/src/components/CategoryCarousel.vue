<template>
  <div class="overflow-hidden">
    <swiper ref="mySwiper" :options="swiperOptions">
      <swiper-slide v-for="cat in datas" :key="cat.id">
        <div @click="openCategory(cat.id)" class="cursor-pointer category-rounded column items-center">
          <div class="category-img">
            <img v-if="cat.filename" :src="cat.src" />
          </div>
          <div class="text-auto q-mt-sm text-weight-medium text-grey-9">
            {{ cat.title }}
          </div>
        </div>
      </swiper-slide>
    </swiper>
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
      swiperOptions: {
        slidesPerView: 4,
        spaceBetween: 5,
        freeMode: true,
      }
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