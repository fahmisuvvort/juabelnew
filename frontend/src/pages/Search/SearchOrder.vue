<template>
  <q-page class="q-pb-lg bg-grey-1">
    <q-header class="text-primary bg-white box-shadow">
       <q-toolbar>
         <q-btn v-go-back.single
            flat round dense
            icon="arrow_back" />
            <q-toolbar-title class="text-weight-bold brand">Tracking Order</q-toolbar-title>
            <q-btn flat label="Cari Produk" :to="{ name: 'ProductSearch'}"></q-btn>
       </q-toolbar>
    </q-header>
    <div class="col bg-white border q-ma-md">
      <q-input ref="input" outlined dense color="grey-2" v-model="search" autofocus @keyup.enter="searchOrder" placeholder="ketik Invoice ID atau Nomor Whatsapp"
      >
      <template v-slot:append>
        <q-icon
          name="search"
          class="cursor-pointer"
          @click="searchOrder"
        />
      </template>
      </q-input>
    </div>
    <div class="q-mt-lg">
  <template v-if="orders.length">
    <div class="bg-white">
      <q-list separator>
        <q-item>
          <q-item-section side class="xs-hide">
           #
          </q-item-section>
          <q-item-section>
            <q-item-label>Detail</q-item-label>
          </q-item-section>
          <q-item-section class="xs-hide">
            <q-item-label>Status</q-item-label>
          </q-item-section>
          <q-item-section side>
            Actions
          </q-item-section>
        </q-item>
        <q-item v-for="(order, index) in orders" :key="order.id">
          <q-item-section side top class="xs-hide">
            {{ index+1 }}
          </q-item-section>
          <q-item-section top>
            <div class="text-xs">
              <table class="dense">
                <tr>
                  <td>INVOICE</td>
                  <td>{{ order.order_ref}}</td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>{{ order.customer_name }}</td>
                </tr>
                <tr>
                  <td>WA</td>
                  <td>{{ order.customer_whatsapp }}</td>
                </tr>
                <tr>
                  <td>Total</td>
                  <td>{{ moneyIDR(order.order_total) }}</td>
                </tr>
                <tr>
                  <td>Dibuat</td>
                  <td>{{ order.created_at }}</td>
                </tr>
                <tr class="sm-hide md-hide lg-hide">
                  <td>Status</td>
                  <td><div><q-badge :color="changeBadgeColor(order.order_status)">{{ order.status_label }}</q-badge></div></td>
                </tr>
              </table>
            </div>
          </q-item-section>
           <q-item-section class="xs-hide">
            <q-item-label> <q-badge :color="changeBadgeColor(order.order_status)">{{ order.status_label }}</q-badge></q-item-label>
          </q-item-section>
          <q-item-section side>
            <div class="column q-gutter-y-sm">
              <q-btn no-caps size="sm" label="Detail" color="purple-7" :to="{name: 'UserInvoice', params: {order_ref: order.order_ref}}"></q-btn>
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
    </template>
     <template v-if="notAvailable">
      <div class="q-pt-xl text-center">Data tidak ditemukan</div>
    </template>
    </div>
    <q-inner-loading :showing="loading">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
  </q-page>
</template>
<script>
import { Api } from 'boot/axios'
export default {
  name: 'SearchOrder',
  data () {
    return {
      loading: false,
      search: '',
      orders: [],
      ready: true,
      notAvailable: false
    }
  },
  methods: {
    searchOrder() {
      this.$refs.input.blur()
      this.notAvailable = false
      this.loading = true
      if(this.search || this.search != '') {
       Api().post('searchOrder', {key: this.search}).then(response => {
         this.search = ''
         if(response.status == 200) {
           this.orders = response.data.results
           if(!this.orders.length) {
             this.notAvailable = true
           }
         }
         this.loading = false
       }).catch(() => {
         this.loading = false
       })
      }
    },
    changeBadgeColor(type) {
      if(type == 'PAID' || type == 'COMPLETE') return 'green'
      if(type == 'PROCESS') return 'blue'
      if(type == 'SHIPPING') return 'teal'
      return 'grey-7'
    },
     money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },

  }
}
</script>

<style>

</style>