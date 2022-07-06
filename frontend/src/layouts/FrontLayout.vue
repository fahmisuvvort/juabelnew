<template>
  <q-layout view="hHh lpR fFf" class="bg-grey-2 front overflow-x-hidden">
      <notify v-if="config && config.is_notifypro"/>
    <q-page-container>
      <router-view />
    </q-page-container>
      <q-footer class="bg-white text-primary footer-tab box-shadow-top">
        <q-tabs
          active-color="primary"
          class="text-grey-8 text-xs"
          no-caps
          dense
          switch-indicator
          indicator-color="primary"
        >
          <q-route-tab
            icon="home"
            label="Beranda"
            :to="{ name: 'Home'}"
            exact
          />

            <q-route-tab 
            icon="search"
            :to="{name: 'ProductSearch'}"
            label="Cari"
            exact
          />

          <q-route-tab 
           v-if="config && config.theme == 'default'"
            icon="store"
            :to="{name: 'ProductIndex'}"
            label="Katalog"
            exact
          />
          <q-route-tab 
           v-if="config && config.theme == 'romance'"
            icon="store"
            :to="{name: 'ProductIndex'}"
            class="bg-primary text-white"
            label="Katalog"
            exact
          />
          <q-btn
          v-if="config && config.theme == 'elegant'"
          :to="{name: 'ProductIndex'}"
           icon="store"
           class="text-md"
           color="primary"
           round
          ></q-btn>

         
          <q-route-tab 
            :to="{name: 'Cart'}" 
            icon="shopping_cart" 
            exact
            label="Keranjang" >
            <q-badge v-if="cartCount > 0" color="pink" floating>{{ cartCount }}</q-badge>
          </q-route-tab>

           <q-tab 
           icon="account_circle" 
           @click="toDashboard"
           exact
           label="Akun" />
        </q-tabs>
      </q-footer>
  </q-layout>
</template>

<script>
import Notify from 'components/Notify.vue'
import { mapGetters, mapState } from 'vuex'
import Cookies from 'js-cookie';
export default {
	components: { Notify },
  name: 'FrontLayout',
  data () {
    return {
      tab: 'images',
      onsearch: false,
      search: '',
    }
  },
  computed: {
    ...mapGetters('cart', ['cartCount']),
    ...mapGetters('product', ['favoriteCount']),
    ...mapState({
      isCheckLogin: state => state.user.isCheckLogin,
      shop: state =>  state.shop,
      config: state => state.config,
      user: state => state.user.user,
    }),
    logoWidth() {
      if(this.shop && this.shop.name) {
        return 'width:35px;height:35px;object:cover'
      } else {
        return 'width:auto;height:35px;object:contain'
      }
    }
  },
  methods: {
    toDashboard() {
      if(this.user){
        if(this.user.role == 'admin') {
          this.$router.push({name: 'Settings'})

        } else if(this.user.role == 'seller') {

          this.$router.push({name: 'SellerHome'})

        } else {
          
          this.$router.push({name: 'CustomerOrder'})
        }
      } else {
        this.$router.push({name: 'Login'})

      }
    }
  },
  mounted() {
    if(! this.shop) {
      this.$store.dispatch('getShop')
    }
    if(! this.config) {
      this.$store.dispatch('getConfig')
    } 
    if(Cookies.get('__token')) {
      if(!this.user) {
        this.$store.dispatch('user/getUser')
      }
    }
  },
  created() {

    if(this.config) {
      this.$store.commit('SET_THEME_COLOR', this.config.theme_color)
    }
  },
  meta() {
    return {
      meta: {
        ogUrl:  { property: 'og:url', content: location.href },
        ogImage1:  { property: 'og:image', content: this.shop?.logo },
      }
      
    }
  }
}
</script>