<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-toolbar-title>
          Dashboard
        </q-toolbar-title>
         <q-btn :to="{ name: 'Home', query: { load: 'true'}}"
          flat icon-right="arrow_forward" label="Lihat Toko" no-caps/>
      </q-toolbar>
    </q-header>

    <div class="overflow-x-hidden relative">
      <q-list separator>
        <q-item clickable v-ripple v-for="item in menus" :key="item.label" :to="{name: item.path}">
          <q-item-section avatar>
            <q-avatar :color="item.color" text-color="white" :icon="item.icon" size="md"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ item.label }}</q-item-label>
            <q-item-label caption lines="1">{{ item.caption }}</q-item-label>
            </q-item-section>
          <q-item-section side>
            <q-icon name="navigate_next" />
          </q-item-section>
        </q-item>
        <q-item clickable v-ripple @click.prevent="logout">
          <q-item-section avatar>
            <q-avatar color="grey" text-color="white" icon="logout" size="md"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>Logout</q-item-label>
            <q-item-label caption lines="1">Keluar Sesi</q-item-label>
            </q-item-section>
          <q-item-section side>
            <q-icon name="navigate_next" />
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </q-page>
</template>

<script>
export default {
  data () {
    return {
      isShowen: false,
      menus: [
        { label: 'Akun', caption: 'Pengaturan Akun', path: 'SellerAccount', icon: 'person', color: 'green'},
        { label: 'Produk', caption: 'Kelola pesanan', path: 'SellerProduct', icon: 'inventory_2', color: 'accent'},
        { label: 'Order', caption: 'Kelola pesanan', path: 'SellerOrder', icon: 'receipt', color: 'blue'},
        { label: 'Gudang Pengiriman', caption: 'Pengaturan toko dan gudang pengiriman', path: 'SellerWarehouse', icon: 'store', color: 'deep-orange'},
      ]
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('user/logout')
    },
    exitApp() {
      this.$store.dispatch('user/exitApp', navigator)
    },
  }
}
</script>