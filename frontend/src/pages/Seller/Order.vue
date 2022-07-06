<template>
  <q-page class="q-pb-xl">
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'SellerHome'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List pesanan
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <div class="q-pt-sm">
      <q-tabs v-model="filter" active-color="primary" outside-arrows>
        <q-tab v-for="option in options" :key="option.value" :name="option.value" :label="option.label" no-caps></q-tab>
      </q-tabs>
    </div>
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
        <q-item v-for="(order, index) in orders.data" :key="index">
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
                  <td>Seller</td>
                  <td>{{ order.seller ? order.seller.name: '-' }}</td>
                </tr>
                <tr>
                  <td>Pembayaran</td>
                  <td>{{ order.transaction? order.transaction.payment_method.split('_').join(' ') : '' }}</td>
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
          <q-item-section side top>
            <div class="column q-gutter-y-sm">
              <!-- <q-btn class="btn-order" unelevated @click="handleFollowUp(order)" no-caps size="12px" :label="messageButtonLabel(order.order_status)" color="green-7"></q-btn> -->
              <q-btn class="btn-order" unelevated v-if="canInputResi(order)" no-caps size="12px" label="Input Resi" color="blue" outline @click="handleInputResi(order)"></q-btn>
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
   <div class="flex justify-center q-py-xl">
     <q-btn outline :loading="orders.isLoadMore" v-if="orders.count > orders.data.length" label="loadmore..." @click="loadMore" unelevated color="primary"></q-btn>
   </div>
    </template>
     <template v-else>
      <div class="text-center q-pt-xl">Tidak ada data</div>
    </template>
    <q-dialog v-model="inputResiModal">
      <q-card square style="width:100%;max-width:420px;">
        <div class="q-px-md q-py-sm bg-dark text-white text-weight-bold">Input Resi  <span v-if="orderSelected"># {{ orderSelected.order_ref }}</span></div>
       <q-form @submit.prevent="submitResi" >
        <q-card-section>
          <div class="text-grey-8">No Resi</div>
          <q-input 
          outlined
          v-model="form.resi"
          :rules="[val => val && val.length > 0 || 'Wajib diisi']"
          />
          <div class="flex justify-end q-mt-sm q-gutter-x-sm">
            <q-btn outline label="Batal" @click.prevent="closeModal" color="primary"></q-btn>
            <q-btn unelevated type="submit" label="Simpan" color="primary"></q-btn>
          </div>
        </q-card-section>
       </q-form>
      </q-card>
    </q-dialog>
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
      isFilter: true,
      options: [
       { value: 'ALL', label: 'Semua'},
       { value: 'UNPAID', label: 'Belum Bayar' },
       { value: 'PAID', label: 'Dibayar'},
       { value: 'PROCESS', label: 'Proses' },
       { value: 'SHIPPING', label: 'Dikirim'}, 
       { value: 'COMPLETE', label: 'Selesai'},
       { value: 'CANCELED', label: 'Batal'}
        ],
      inputResiModal: false,
      orderSelected: '',
      followUpModal: false,
      currentOrder: null,
      search: '',
      filter: 'ALL',
      form: {
        order_id: '',
        resi: '',
        status: ''
      },
    }
  },
  watch: {
    'filter': function(newVal, oldVal) {
      if(newVal != oldVal) {
        this.form.status = newVal
        this.filterOrder(newVal)
      }
    }
  },
  computed: {
    ...mapState({
      orders: state => state.seller.orders,
      loading: state => state.loading
    }),
  },
  created() {
    if(this.$route.query.filter) {
      this.filter = this.$route.query.filter
      this.filterOrder(this.filter)

    } else {
      this.getOrder()
    }
  },
  methods: {
    ...mapActions('seller', ['getOrder', 'paginateOrder', 'filterOrder']),
    loadMore() {
      this.paginateOrder({ filter: this.filter, skip: this.orders.data.length })
    },
    handleSelectMode(evt) {
      this.isFilter = !this.isFilter
      this.search = ''
      this.filter = ''
    },
    changeTab(evt) {
      this.$router.push({ name: 'SellerOrder', query: { filter: evt }})
      this.search = ''
      this.filterOrder(evt)
    },
    handleKirimCod(order) {
      this.$q.dialog({
        title: 'Konfirmasi',
        message: 'Akan mengirim pesanan sekarang?, ini akan merubah status pesanan menjadi "sedang dikirim"',
        cancel: true,
      }).onOk(() => {
        this.form.status = 'SHIPPING'
        this.form.order_id = order.id
        this.handleUpdateStatusOrder()
      })
    },
    handleUpdateStatusOrder() {
      this.$store.commit('SET_LOADING', true)
      this.updateStatusOrder(this.form).then(() => {
        this.filter = this.form.status 
        this.filterOrder(this.filter)
        
      }).finally(() =>  this.$store.commit('SET_LOADING', false))
    },
    handleCancelOrder(order) {
      this.$q.dialog({
        title: 'Konfirmasi Pembatalan order',
        message: 'Akan membatalkan order ini?, perubahan ini tidak dapat dikembalikan',
        cancel: true,
      }).onOk(() => {
        this.form.status = 'CANCELED'
        this.form.order_id = order.id
        this.cancelOrder(order.id).then(() => {
          this.filter = this.form.status 
          this.filterOrder(this.filter)
        })
      })
    },
    canShip(order) {
      if(order.shipping_courier_name == 'COD') {
        if(order.order_status == 'PROCESS' || order.order_status == 'UNPAID') {
          return true
        } else {
          return false
        }
      } else {
        return false
      }
    },
    canComplete(order) {
      return order.order_status == 'SHIPPING' ? true : false
    },
    canCancelOrder(order) {
      return order.order_status == 'UNPAID'
    },
    handleCompletionOrder(order) {
    
      this.$q.dialog({
        title: 'Konfirmasi',
        message: 'Ini akan merubah status pesanan menjadi "selesai"',
        cancel: true,
      }).onOk(() => {
        this.form.status = 'COMPLETE'
        this.form.order_id = order.id
        this.handleUpdateStatusOrder()
      })
      
    },
    handleSearchOrder() {
      if(this.search) {

        this.$store.commit('SET_LOADING', true)
        this.searchOrder(this.search)
      }
    },
    resetOrder() {
      this.orderFiltered = []
      this.isFilter = false
      this.search = ''
      this.filter = ''
    },
    changeBadgeColor(type) {
      if(type == 'PAID' || type == 'SHIPPING') return 'teal'
      if(type == 'PROCESS') return 'blue'
      if(type == 'COMPLETE') return 'green'
      if(type == 'CANCELED') return 'red'
      return 'grey-7'
    },
    canConfirm(order) {
      if(order.shipping_courier_name != 'COD') {
        if(order.order_status == 'UNPAID' || order.order_status == 'OVERDUE') return true

        return false
      } else {
        return false

      }
    },
    canInputResi(order) {
      if(order.shipping_courier_name == 'COD') {
        return false
      } else {
        if(order.order_status == 'PROCESS' || order.order_status == 'PAID') { 
          return true
        } else {
          return false
        }
      }

    },
    messageButtonLabel(status) {
      if(status == 'UNPAID' || status == 'OVERDUE') return 'FollowUp Order'
      return 'Kirim Pesan'
    },
    handleDeleteOrder(id) {
      this.$q.dialog({
        title: 'Yakin akan menghapus data?',
        message: 'data yang dihapus tidak dapat dikembalikan.',
        ok: {label: 'Hapus', flat: true, 'no-caps': true, 'color' :'red-7'},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
        }).onOk(() => {
          this.destroyOrder(id).then(response => {
              if(response.status == 200) {
                this.filterOrder(this.filter)
              }
            })
        }).onCancel(() => {
        }).onDismiss(() => {
      })
    },
    handleConfirmationOrder(id) {
      this.$q.dialog({
        title: 'Konfirmasi pembayaran',
        message: 'Pastikan pembayaran telah anda terima dengan baik',
        ok: {label: 'Konfirmasi', flat: true, 'no-caps': true, 'color' :'green-7'},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
        }).onOk(() => {
          this.acceptPayment(id).then(() => {
            this.filter = 'PROCESS'
            this.filterOrder(this.filter)
          })
        }).onCancel(() => {
        }).onDismiss(() => {
      })
    },
    handleFollowUp(data) {
      this.currentOrder = data
      this.followUpModal = true
    },
    handleInputResi(order) {
      this.form.resi = ''
      this.orderSelected = order
      this.form.order_id = order.id
      this.inputResiModal = true
    },
    closeModal() {
      this.inputResiModal = false
      this.orderSelected = ''
      this.form.order_id = ''
    },
    submitResi() {
      this.inputResi(this.form).then(() => {
        this.filter = 'SHIPPING'
        this.filterOrder(this.filter)
      })
      this.closeModal()
    }
  },
  
}
</script>

<style scoped>
.btn-order {
  min-width:114px;
  width:100%;
}
</style>