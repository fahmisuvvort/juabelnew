<template>
<div class="overflow-hidden">
    <swiper class="swiper" :options="swiperOptions">
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
        spaceBetween: 30,
        centeredSlides: true,
        autoHeight: true, //enable auto height
        autoplay: {
          delay: 4500,
          disableOnInteraction: false
        },
    }
    }
  }

}
</script>
