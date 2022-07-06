<template>
  <div id="q-app" class="mobile-view glass bg-white">
    <router-view />
    <theme-setting />
  </div>
</template>
<script>
import ThemeSetting from 'components/ThemeSetting.vue'
export default {
  name: 'App',
  components: { ThemeSetting },
  computed: {
    session_id() {
      return this.$store.state.session_id
    }
  },
  mounted() {
    if(! this.session_id) {
      this.makeSessionId()
    } else {
      this.$store.dispatch('cart/getCarts', this.session_id)
    }
  },
  created() {
    this.$store.commit('REMOVE_INSTALL_APP')
    window.addEventListener('beforeinstallprompt', (e) => {
      this.$store.commit('SET_INSTALL_APP', e)
    })

    window.addEventListener('appinstalled', () => {
      this.$store.commit('REMOVE_INSTALL_APP')
    })
  },
  methods: {
    makeSessionId() {
      var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

      var result = '';
        for ( var i = 0; i < 60; i++ ) {
            result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }

        this.$store.commit('SET_SESSION_ID', result);
    },
  },
  meta: {
    meta: {
      equiv: { 'http-equiv': 'Content-Type', content: 'text/html; charset=UTF-8' },
      // note: for Open Graph type metadata you will need to use SSR, to ensure page is rendered by the server
      ogUrl:  { property: 'og:url', content: location.href },
    },
    noscript: {
      default: 'This is content for browsers with no JS (or disabled JS)'
    }
  }
}
</script>
