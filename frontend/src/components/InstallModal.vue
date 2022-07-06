<template>
  <q-card class="install-modal sans">
    <q-card-section>
      <div class="row no-wrap items-center">
        <div class="q-mr-sm">
          <img v-if="shop && shop.logo_url" :src="shop.logo_url" width="55" height="55"/>
          <img v-else src="/icon/icon-96x96.png" width="55" height="55"/>
        </div>
        <div>
          <div v-if="shop" class="text-weight-bold text-md text-primary">{{ shop.name }} App</div>
          <div class="text-grey-6 text-sm">Berbelanja akan lebih cepat dengan menggunakan aplikasi.</div>
        </div>
      </div>
      <div class="column q-mt-md q-gutter-y-xs">
        <q-btn @click="installNow" no-caps unelevated icon="download" color="primary">Install Sekarang</q-btn>
        <q-btn @click="installLater" label="Nanti Saja" no-caps color="primary" flat></q-btn>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'InstallModal',
  data() {
    return {
      installPromotionModal: false,
      maybeLater: false,
      deferredPrompt: null
    }
  },
  computed: {
    shop: function() {
      return this.$store.state.shop
    },
  },
  methods: {
    checkIsInstalled () {

      let isInstalled = false
        window.navigator.getInstalledRelatedApps().then(relatedApps => {

          let n = relatedApps.filter(app => app.id == 'wacommerce')
            if(n.length) {
              isInstalled = true
            }
        });
        
        return isInstalled
    },
    listenInstallPrompt() {
      window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault()
        this.deferredPrompt = e
        this.$store.commit('SET_INSTALL_APP', this.deferredPrompt)
        if(!this.checkIsInstalled()) {
          if(localStorage.getItem('maybeLater')) {
            setTimeout(() => {
              this.showInstallPromotion()
            }, 3600000)
          } else {
            this.showInstallPromotion()
          }
        }
        
      })
    },
    showInstallPromotion() {
      this.installPromotionModal = true
    },
    hideInstallPromotion() {
      this.installPromotionModal = false
    },
    async installNow() {
      this.hideInstallPromotion()

      if (this.deferredPrompt !== null) {

        this.deferredPrompt.prompt();

        const { outcome } = await this.deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            this.deferredPrompt = null;
            localStorage.removeItem('maybeLater',true)
            this.$store.commit('REMOVE_INSTALL_APP')
        }
      }

    },
    installLater() {
      this.hideInstallPromotion()
      localStorage.setItem('maybeLater',true)
      this.$store.commit('SET_INSTALL_APP', this.deferredPrompt)
    },
  }
}
</script>

<style>

</style>