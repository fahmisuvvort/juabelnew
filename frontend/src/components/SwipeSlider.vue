<template>
<div class="overflow-hidden">
    <swiper ref="mySwiper" :options="swiperOptions">
      <swiper-slide v-for="(img, index) in datas" :key="index">
        <div class="auto-padding">
          <img :src="img.src" style="width:100%;height:auto;border-radius:6px;"/>
        </div>
      </swiper-slide>
    </swiper>
  </div>
</template>

<script>
export default {
  name: 'Front',
  props: {
    datas: Array
  },
  data () {
    return {
      swiperOptions: {
          slidesPerView: 1,
          spaceBetween: 5,
        }
    }
  }

}
</script>

<style>

</style>