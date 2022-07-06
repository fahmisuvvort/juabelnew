<template>
<div class="overflow-hidden">
   <vue-glide :options="glideOptions">
      <vue-glide-slide v-for="(img, index) in datas" :key="index">
        <div class="auto-padding">
          <img :src="img.src" style="width:100%;height:auto;border-radius:6px;"/>
        </div>
      </vue-glide-slide>
      <template slot="control" data-glide-el="controls[nav]" v-if="datas.length > 1">
         <div class="glide__bullets" data-glide-el="controls[nav]">
          <button v-for="(d,i) in datas.length" :key="i" class="glide__bullet" :data-glide-dir="'='+ i"></button>
        </div>
      </template>
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