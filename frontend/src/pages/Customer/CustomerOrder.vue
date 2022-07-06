<template>
  <q-page class="q-pb-xl">
    <template v-if="orders.data.length">
    <div>
      <q-list separator>
        <q-item>
          <q-item-section side class="xs-hide">
           #
          </q-item-section>
          <q-item-section>
            <q-item-label>Detail</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-item-label>Actions</q-item-label>
          </q-item-section>
        </q-item>
        <q-item v-for="(order, index) in orders.data" :key="order.id">
          <q-item-section side top class="xs-hide">
            {{ index+1 }}
          </q-item-section>
          <q-item-section top>
            <div class="text-sm1">
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
                  <td>{{ moneyIDR(order.grand_total) }}</td>
                </tr>
                <tr>
                  <td>Dibuat</td>
                  <td>{{ order.created_at }}</td>
                </tr>
                <tr>
                  <td>Pengiriman</td>
                  <td>{{ order.shipping_courier_name }}</td>
                </tr>
                <tr>
                  <td style="vertical-align:top;">Pembayaran</td>
                  <td><div v-html="order.transaction? order.transaction.payment_name : ''"></div></td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>
                    <q-badge :color="changeBadgeColor(order.order_status)">{{ order.status_label }}</q-badge>
                  </td>
                </tr>
              </table>
            </div>
          </q-item-section>
          <q-item-section side>
            <div class="column q-gutter-y-sm">
              <q-btn unelevated no-caps size="13px" label="Detail" color="purple-7" :to="{name: 'UserInvoice', params: {order_ref: order.order_ref}}"></q-btn>
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
   <div class="flex justify-center q-py-xl">
     <q-btn outline :loading="orders.isLoadMore" v-if="orders.canLoadMore && !isFilter && !isSearch" label="loadmore..." @click="loadMore" unelevated color="primary"></q-btn>
   </div>
    </template>
     <template v-else>
      <div class="text-center q-pt-xl q-mt-xl">Tidak ada data</div>
    </template>
    <q-inner-loading :showing="loading">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'OrderIndex',
  data() {
    return {
      isSearch: true,
      options: ['UNPAID', 'PAID', 'PROCESS', 'SHIPPING', 'CANCELED'],
      isFilter: false,
      inputResiModal: false,
      orderSelected: '',
      followUpModal: false,
      currentOrder: null,
      orderFiltered: [],
      orderBySearch: [],
      search: '',
      form: {
        order_id: '',
        resi: '',
        status: ''
      },
    }
  },
  computed: {
    ...mapState({
      orders: state => state.order.customer_order,
      loading: state => state.loading
    }),
  },
  created() {
    this.getCustomerOrders()
  },
  methods: {
    ...mapActions('order', ['getCustomerOrders', 'getPaginateCustomerOrder', 'filterOrder']),
    loadMore() {
      this.getPaginateCustomerOrder(this.orders.data.length)
    },

    handleSelectMode() {
      this.isSearch = !this.isSearch
      this.search = ''
    },

    handleSearchOrder() {
      this.$store.commit('SET_LOADING', true)
      this.searchAdminOrder({key: this.search})
    },
    handleFilterOrder() {
      if(this.search) {
        this.filterOrder({key: this.search})
      }
    },
    resetOrder() {
      this.orderFiltered = []
      this.isFilter = false
      this.search = ''
    },
    changeBadgeColor(type) {
      if(type == 'PAID' || type == 'SHIPPING') return 'teal'
      if(type == 'PROCESS') return 'blue'
      if(type == 'COMPLETE') return 'green'
      if(type == 'CANCELED') return 'red'
      return 'grey-7'
    },
    messageButtonLabel(status) {
      if(status == 'UNPAID' || status == 'OVERDUE') return 'Follow Up Order'
      return 'Kirim Pesan'
    },
    money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },
 
  },
  
}
</script>
