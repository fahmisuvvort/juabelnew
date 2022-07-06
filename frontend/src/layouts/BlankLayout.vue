<template>
  <q-layout view="hHh lpR fFf" class="front">
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { mapState } from 'vuex'
import Cookies from 'js-cookie';
export default {
  name: 'BlankLayout',
  computed: {
    ...mapState({
      shop: state => state.shop,
      config: state => state.config,
      user: state => state.user.user,
    })
  },
  created() {
    if(! this.shop) {
      this.$store.dispatch('getShop')
    }
    if(! this.config) {
      this.$store.dispatch('getConfig')
    } else {
      document.body.style.setProperty('--q-color-primary', this.config.theme_color)
    }
    if(Cookies.get('__token')) {
      if(!this.user) {
        this.$store.dispatch('user/getUser')
      }
    }
  },
  meta() {
    return {
      meta: {
        ogUrl:  { property: 'og:url', content: location.href },
        ogImage:  { property: 'og:image', content: this.shop?.logo },
      }
      
    }
  }
}
</script>