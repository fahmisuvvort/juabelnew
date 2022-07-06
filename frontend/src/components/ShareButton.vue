<template>
   <q-btn v-if="webShareApiSupported" @click="shareTheWeb" color="white" unelevated dense round size="md" icon="share" text-color="grey-9" >
    </q-btn>
</template>

<script>
export default {
  props: ['image_url'],
  computed: {
    webShareApiSupported() {
      return navigator.share
    }
  },
  methods: {
    shareTheWeb() {
      const title = document.title;
      navigator.share({
        title: title,
        text: this.image_url,
        url: document.location.href,
      })
    },
  }
}
</script>
