<template>
<div class="overflow-hidden">
  <vue-glide :options="glideOptions">
      <vue-glide-slide v-for="(img, index) in datas" :key="index">
         <div class="auto-padding">
          <img :src="img.src" style="width:100%;height:auto;border-radius:6px;"/>
        </div>
      </vue-glide-slide>
    </vue-glide>  
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
      glideOptions: {
        gap:10,
        perView: 1,
        animationDuration: 1000,
        autoplay: 6000,
        bullet: true
      },
    }
  }

}
</script>
