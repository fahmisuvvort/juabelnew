<template>
  <div class="overflow-hidden">
     <vue-glide :options="glideOptions">
      <vue-glide-slide v-for="cat in datas" :key="cat.id">
        <div class="column full-height relative bg-white wisata-card">
          <q-img v-if="cat.filename" :src="cat.src" ratio="1" @click="openCategory(cat.id)" class="cursor-pointer rounded-borders">
          </q-img>
        </div>
         <div class="text-category-auto text-center q-mt-xs">{{ cat.title }}</div>
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
      if(this.datas.length <= 3 || window.innerWidth < 360) {
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