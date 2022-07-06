<template>
  <q-layout view="hHh lpR fFf">
    <q-page-container>
        <router-view />
    </q-page-container>
    <!-- <q-footer class="text-center text-xs text-grey-7 bg-white q-pa-md">&copy; Copyright 2021 {{ shop? shop.name : '' }} Allrights Reserved</q-footer> -->
  </q-layout>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name: 'AdminLayout',
  computed: {
        ...mapState({
      isCheckLogin: state => state.user.isCheckLogin,
      shop: state => state.shop,
      user: state => state.user
    })
  },
  created() {
    this.$store.dispatch('getAdminConfig')
    if(!this.shop) {
      
      this.$store.dispatch('getShop')
    }
    if(! this.user) {

      this.$store.dispatch('user/getUser')
    }
  }
}
</script>
