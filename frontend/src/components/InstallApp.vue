<template>
  <div>
    <div class="q-py-md bg-white sans" v-if="canInstall"
    :class="{'q-my-md' : spacing}"
    >
      <q-list>
        <q-item>
          <q-item-section avatar>
            <img v-if="shop && shop.logo_url" :src="shop.logo_url" width="50" height="50"/>
            <img v-else src="/icon/icon-96x96.png" width="50" height="50"/>
          </q-item-section>
          <q-item-section top>
            <div class="text-weight-bold text-md text-primary">{{ shop.name }} App</div>
            <q-item-label caption>Berbelanja akan lebih mudah dan cepat dengan menggunakan aplikasi.</q-item-label>
          </q-item-section>
          <q-item-section side v-if="!isMini">
            <div>
              <q-btn icon="download" unelevated rounded @click="installNow" no-caps unzelevated color="primary">
                <span>Install</span>
              </q-btn>
            </div>
          </q-item-section>
        </q-item>
        <q-item v-if="isMini">
          <q-item-section>
              <q-btn unelevated @click="installNow" no-caps unzelevated color="primary">
                <span>Install Sekarang</span>
              </q-btn>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    spacing: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
  },
  computed: {
    ...mapState({
      shop: state => state.shop,
      deferredPrompt: state => state.deferredPrompt
    }),
    isMini: () => {
      return window.screen.width < 480 ? true : false
    },
    canInstall: function() {
      return this.deferredPrompt ? true : false
    },
  },  
 
  methods: {
    installNow() {
      this.deferredPrompt.prompt();
    },
  },

}
</script>

<style>

</style>