<template>
  <div class="overflow-hidden block-category">
    <vue-glide :options="glideOptions">
      <vue-glide-slide v-for="cat in datas" :key="cat.id">
        <div @click="openCategory(cat.id)" class="cursor-pointer column items-center">
          <div class="image">
            <img v-if="cat.filename" :src="cat.src" />
          </div>
           <div class="text-category-auto text-center q-mt-xs">{{ cat.title }}</div>
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
      glideOptions: {
        rewind: false,
        perView: 4,
        gap: 12,
        bound: true
      }
    }
  },
  created() {
    this.setGlideOptions()
  },
  methods: {
    setGlideOptions() {
      if(this.datas.length <= 3) {
      this.glideOptions.perView = 3
      }else if(window.innerWidth > 600) {
        this.glideOptions.perView = 5
        this.glideOptions.gap = 16
      }else if(window.innerWidth < 360) {
        this.glideOptions.perView = 3
        this.glideOptions.gap = 8
      }
    },
    openCategory(id) {
      if(id) {
        this.$router.push({name: 'ProductCategory', params: {id:id}})
      }
    }
  }

}
</script>