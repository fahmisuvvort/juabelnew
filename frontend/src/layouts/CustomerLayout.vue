<template>
  <q-layout view="hHh lpR fFf">
     <q-header>
      <q-toolbar>
        <q-toolbar-title>
         Dashboard
        </q-toolbar-title> 
          <q-btn :to="{ name: 'Home'}" stretch
          flat icon-right="arrow_forward" label="Beranda" no-caps/>
      </q-toolbar>
      <q-toolbar class="bg-grey-1">
          <!-- <q-separator vertical color="primary" /> -->
        <q-btn stretch color="primary" flat label="Pesanan Saya" no-caps :to="{name: 'CustomerOrder'}"/>
          <q-separator vertical color="primary" />
          <q-btn stretch color="primary" flat label="Akun Saya" no-caps :to="{name: 'CustomerAccount'}"/>
          <q-space></q-space>
        <q-btn 
          stretch 
          color="primary"
          @click="LogOut"
          flat
          no-caps
          label="Logout" />
          <!-- <q-separator vertical color="primary" /> -->
      </q-toolbar>
      </q-header>
    <q-page-container>
        <router-view />
    </q-page-container>
  </q-layout>
</template>
<script>
import { mapState } from 'vuex'
import Cookies from 'js-cookie';
export default {
  name: 'AdminLayout',
  computed: {
        ...mapState({
      isCheckLogin: state => state.user.isCheckLogin,
      shop: state => state.shop,
      user: state => state.user.user,
    })
  },
  created() {
    this.$store.dispatch('getAdminConfig')
    if(!this.shop) {
      this.$store.dispatch('getShop')
    }
    if(Cookies.get('__token')) {
      if(!this.user) {
        this.$store.dispatch('user/getUser')
      }
    }
  },
  methods: {
     LogOut() {
      this.$store.dispatch('user/logout')
    },
  }
}
</script>
