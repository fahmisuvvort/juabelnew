<template>
  <q-carousel
        v-model="slide"
        swipeable
        animated
        control-color="grey-3"
        navigation
        padding
        height="300px"
      >
         <q-carousel-slide v-for="(item, index) in datas" :key="index" :name="index">
          <div class="row fit justify-start items-center no-wrap">
            <q-img class="rounded-borders full-height" :src="item.src" />
          </div>
        </q-carousel-slide>
  </q-carousel>
</template>

<script>
export default {
  data() {
    return {
      slide: ''
    }
  },
  props: {
    datas: Array
  }
}
</script>
