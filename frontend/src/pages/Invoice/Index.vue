<template>
  <q-page class="q-pb-lg bg-grey-2">
    <q-header class="text-primary bg-white no-print box-shadow">
      <q-toolbar>
        <q-btn :to="{name: 'CustomerOrder'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title v-if="invoice" class="text-weight-bold brand">Invoice {{ invoice.order_ref }}</q-toolbar-title>
      <q-btn icon="print" @click="printInvoice" flat round class="no-print"></q-btn>
      </q-toolbar>
    </q-header>
    <div v-if="invoice" class="no-print q-pt-md">
      <div class="q-gutter-y-md">
        <div class="q-pb-lg bg-white q-px-md q-pt-md">
          <div class="flex justify-between text-grey-8 q-py-md">
            <div class="q-mb-sm">
              <div class="text-weight-bold q-mb-xs">{{ shop.name }}</div>
              <div class="">{{ shop.phone }}</div>
              <div class="text-weight-thin" v-html="shop.address"></div>
            </div>
            <div>
              <table>
                <tr>
                  <td>Invoice</td>
                  <td>: {{ invoice.order_ref }}</td>
                </tr>
                <tr>
                  <td>Referensi</td>
                  <td>: {{ invoice.transaction.payment_ref }}</td>
                </tr>
                <tr>
                  <td>Dibuat</td>
                  <td>: {{ invoice.created_at }}</td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div class="full-width q-px-md q-py-xs text-white text-center" :class="statusColor(invoice.order_status)">
                      {{ invoice.status_label }}
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="q-mt-lg text-grey-8">
            <table class="table-order-item" v-if="invoice.items">
              <tr>
                <th align="left">Item</th>
                <th align="left">qty</th>
                <th align="right">Harga</th>
              </tr>
              <tr v-for="(item, index) in invoice.items" :key="index">
                <td class="">
                  <div>{{ item.name }}</div>
                  <div class="text-caption tet-grey-6">{{ item.note }}</div>
                </td>
                <td>{{ item.quantity }}</td>
                <td align="right">{{ moneyIDR(item.price) }}</td>
              </tr>
            </table>
            <div class="column justify-end items-end q-py-sm">
              <table class="dense">
                <tr>
                  <td align="right">SubTotal</td>
                  <td>:</td>
                  <td align="right">{{ moneyIDR(invoice.order_subtotal) }}</td>
                </tr>
                <tr>
                  <td align="right">Ongkos Kirim</td>
                  <td>:</td>
                  <td align="right">{{ moneyIDR(invoice.shipping_cost) }}</td>
                </tr>
                <tr v-if="invoice.payment_fee > 0">
                  <td align="right">Payment Fee</td>
                  <td>:</td>
                  <td align="right">{{ moneyIDR(invoice.payment_fee) }}</td>
                </tr>
              </table>
            </div>
            <div class="flex justify-end q-py-sm bg-grey-1">
              <table class="table dense">
                <tr v-if="invoice.order_unique_code">
                  <td align="right">Kode Unik (-)</td>
                  <td>:</td>
                  <td align="right">{{ invoice.order_unique_code }}</td>
                </tr>
                <tr v-if="invoice.discount">
                  <td align="right">Diskon (-)</td>
                  <td align="right">:</td>
                  <td align="right">{{ invoice.discount? moneyIDR(invoice.discount) : 0 }}</td>
                </tr>
                <tr>
                  <th align="right">Total Tagihan</th>
                  <td align="right">:</td>
                  <th align="right">{{ moneyIDR(invoice.grand_total) }}</th>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <q-card class="shadow" flat square>
            <div class="card-heading">Customer</div>
          <q-card-section>
            <div class="text-grey-9">
              <table class="dense">
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td>{{ invoice.customer_name }}</td>
                </tr>
                <tr>
                  <td>Whatsapp</td>
                  <td>:</td>
                  <td>{{ invoice.customer_whatsapp }}</td>
                </tr>
                <tr>
                  <td style="vertical-align:top;">Alamat</td>
                  <td style="vertical-align:top;">:</td>
                  <td> <div v-html="invoice.shipping_address"></div> </td>
                </tr>
              </table>
            </div>
          </q-card-section>
        </q-card>
        <q-card class="bg-white shadow" square>
            <div class="card-heading">Ekspedisi</div>
          <q-card-section>
            <div class="text-grey-9">
              <table class="dense">
                <tr>
                  <td>Kurir</td>
                  <td>:</td>
                  <td>{{ invoice.shipping_courier_name }}</td>
                </tr>
                <tr>
                  <td>Service</td>
                  <td>:</td>
                  <td>{{ invoice.shipping_courier_service ? invoice.shipping_courier_service : '-' }}</td>
                </tr>
                <tr>
                  <td>No Resi</td>
                  <td>:</td>
                  <td>{{ invoice.shipping_courier_code? invoice.shipping_courier_code : '-'  }}</td>
                </tr>
              </table>
            </div>
          </q-card-section>
        </q-card>
        <q-card class="bg-white shadow" square>
          <div class="card-heading border-b">Pembayaran</div>
          <q-card-section>
              <table class="dense">
                <tr>
                  <td>Metode</td>
                  <td>:</td>
                  <td>{{ invoice.transaction.payment_type.split('_').join(' ') }}</td>
                </tr>
                <tr>
                  <td style="vertical-align:top;">Detil</td>
                  <td style="vertical-align:top;">:</td>
                  <td><div v-html="invoice.transaction.payment_name"></div></td>
                </tr>
                <tr>
                  <td>Referensi</td>
                  <td>:</td>
                  <td>{{ invoice.transaction.payment_ref }}</td>
                </tr>
              </table>
          </q-card-section>
        </q-card>
      </div>
      <q-dialog 
      v-model="modalPayment"
      persistent
      maximized
      seamless
      transition-show="slide-up"
      transition-hide="slide-down"
      >
        <q-card flat square class="max-width bg-grey-2">
            <q-toolbar class="bg-white sticky-top border-b">
              <q-toolbar-title><span class="text-weight-bold card-heading">Pembayaran</span></q-toolbar-title>
              <q-btn flat round dense icon="close" v-close-popup />
            </q-toolbar>
            <keep-alive>
            <component v-bind:is="isPaymentType" v-bind:transaction="invoice.transaction" @kirimBukti="kirimBuktiTransfer"></component>
            </keep-alive>
        </q-card>
      </q-dialog>
      <q-footer class="bg-grey-2 q-pa-md" v-if="invoice.order_status == 'UNPAID'">
        <div class="text-center text-grey-7 column q-gutter-y-sm">
            <q-btn v-if="invoice && invoice.transaction.payment_method != 'COD'" label="Instruksi Pembayaran" no-caps icon="payments" @click.prevent="handlePaymentModal" color="green-7"></q-btn>
            <q-btn ref="chatAdmin" label="Chat admin" icon="chat" no-caps  @click="chatToAdmin" color="blue-7"></q-btn>
        </div>
      </q-footer>
    </div>
    <q-inner-loading :showing="loading" class="no-print">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    <div v-if="invoice" :class="{'no-print': isPrintPacking}">
      <div class="print-invoice">
        <div class="">
          <div class="bg-white">
            <div class="text-lg text-weight-bold">Invoice</div>
            <div class="q-mb-md flex justify-between item-start">
              <table>
                  <tr>
                    <th align="left">No</th>
                    <th align="left">: {{ invoice.order_ref }}</th>
                  </tr>
                  <tr>
                    <td align="left">Dibuat</td>
                    <td>: {{ invoice.created_at }}</td>
                  </tr>
                  <!-- <tr>
                    <td align="left">Status</td>
                    <td>: {{ invoice.status_label }}</td>
                  </tr> -->
                </table>
              <div>
                <!-- <div>Metode Pembayaran</div> -->
                <div class="text-center border q-pa-sm q-mb-sm text-weight-bold" style="border:1px solid #efefef;">{{ invoice.status_label }}</div>
                <div>{{ invoice.transaction.payment_name }}</div>
              </div>
            </div>
            <div class="flex justify-between">
              <div class="">
                <div class="text-weight-medium q-mb-xs">Ditagihkan Kepada:</div>
                <div class="text-weight-bold">{{ invoice.customer_name }}</div>
                <div>{{ invoice.customer_whatsapp }}</div>
                <div v-html="invoice.shipping_address"></div> 
              </div>
              <div class="">
                <div class="text-weight-medium q-mb-xs">Dibayarkan Kepada:</div>
                <div class="text-weight-bold q-mb-xs">{{ shop.name }}</div>
                <div class="">{{ shop.phone }}</div>
                <div class="">{{ shop.app_url }}</div>
              </div>
            </div>
            <div class="q-mt-sm">
              <div class="text-weight-bold q-mb-xs">Detil Pesanan:</div>
              <table class="table-order-item" v-if="invoice.items">
                <tr>
                  <th align="left">Item</th>
                  <th align="left">qty</th>
                  <th align="right">Harga</th>
                </tr>
                <tr v-for="(item, index) in invoice.items" :key="index">
                  <td class="">
                    <div>{{ item.name }}</div>
                    <div class="text-caption tet-grey-6">{{ item.note }}</div>
                  </td>
                  <td>{{ item.quantity }}</td>
                  <td align="right">{{ moneyIDR(item.price) }}</td>
                </tr>
              </table>
              <div class="column justify-end items-end q-mt-sm">
                <table>
                  <tr>
                    <td align="right">SubTotal</td>
                    <td>:</td>
                    <td align="right">{{ moneyIDR(invoice.order_subtotal) }}</td>
                  </tr>
                  <tr>
                    <td align="right">Ongkos Kirim</td>
                    <td>:</td>
                    <td align="right">{{ moneyIDR(invoice.shipping_cost) }}</td>
                  </tr>
                  <tr v-if="invoice.payment_fee > 0">
                    <td align="right">Payment Fee</td>
                    <td>:</td>
                    <td align="right">{{ moneyIDR(invoice.payment_fee) }}</td>
                  </tr>
                  <tr v-if="invoice.order_unique_code">
                    <td align="right">Kode Unik (-)</td>
                    <td>:</td>
                    <td align="right">{{ invoice.order_unique_code }}</td>
                  </tr>
                  <tr v-if="invoice.discount">
                    <td align="right">Diskon (-)</td>
                    <td align="right">:</td>
                    <td align="right">{{ invoice.discount? moneyIDR(invoice.discount) : 0 }}</td>
                  </tr>
                  <tr>
                    <th align="right">Total Tagihan</th>
                    <td align="right">:</td>
                    <th align="right">{{ moneyIDR(invoice.grand_total) }}</th>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="invoice" :class="{'no-print': isPrintInvoice}">
      <div class="print-packing">
        <div class="bg-white">
          <div class="flex justify-between">
            <div class="">
              <div class="text-weight-medium q-mb-xs text-weight-bold">Penerima:</div>
              <table>
                <tr>
                  <td align="left">Nama</td>
                  <td align="left">:</td>
                  <td align="left">{{ invoice.customer_name }}</td>
                </tr>
                <tr>
                  <td align="left">No Ponsel</td>
                  <td align="left">:</td>
                  <td align="left">{{ invoice.customer_whatsapp }}</td>
                </tr>
              </table>
              <div class="q-mt-sm text-weight-bold">Alamat:</div>
              <div v-html="invoice.shipping_address"></div> 
            </div>
            <div class="">
              <div class="text-weight-medium q-mb-xs text-weight-bold">Pengirim:</div>
              <table>
                <tr>
                  <td align="left">Toko</td>
                  <td align="left">:</td>
                  <th align="left">{{ shop.name }}</th>
                </tr>
                <tr>
                  <td align="left">No Ponsel</td>
                  <td align="left">:</td>
                  <td align="left">{{ shop.phone }}</td>
                </tr>
              </table>
              <div class="q-pl-xs">{{ shop.app_url }}</div>
              <div v-if="shop.address">
                <div class="q-mt-sm text-weight-bold">Alamat Toko:</div>
                <div v-html="shop.address"></div>
              </div>
            </div>
          </div>
          <div class="q-mt-sm">
            <div class="q-mb-xs text-weight-bold">Detil Pesanan:</div>
            <table class="table-order-item" v-if="invoice.items">
              <tr>
                <th align="left">Item</th>
                <th align="left">qty</th>
              </tr>
              <tr v-for="(item, index) in invoice.items" :key="index">
                <td class="">
                  <div>{{ item.name }}</div>
                  <div class="text-caption tet-grey-6">{{ item.note }}</div>
                </td>
                <td>{{ item.quantity }}</td>
              </tr>
            </table>
            <div class="flex justify-end q-py-sm bg-grey-1">
              <table class="table dense">
                <tr>
                  <th align="right">Total Pesanan</th>
                  <td align="right">:</td>
                  <th align="right">{{ moneyIDR(invoice.grand_total) }}</th>
                </tr>
              </table>
            </div>
          </div>
          <div class="">
            <div class="q-mb-xs text-weight-bold">Kurir:</div>
            <table>
              <tr>
                <td>Kurir</td>
                <td>:</td>
                <td>{{ invoice.shipping_courier_name }}</td>
              </tr>
              <tr>
                <td>Service</td>
                <td>:</td>
                <td>{{ invoice.shipping_courier_service ? invoice.shipping_courier_service : '-' }}</td>
              </tr>
              <tr>
                <td>No Resi</td>
                <td>:</td>
                <td>{{ invoice.shipping_courier_code? invoice.shipping_courier_code : '-'  }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { copyToClipboard } from 'quasar'
import { mapActions, mapState } from 'vuex'
import PaymentGateway from './PaymentGateway.vue'
import DirectPayment from './DirectPayment.vue'
export default {
  name: 'InvoiceIndex',
  components: { PaymentGateway, DirectPayment },
  data () {
    return {
      modalPayment: false,
      throtle: 1,
      interval: null,
      isPrintPacking: false,
      isPrintInvoice: false,
      timeout: null,
      whatsappUrl: 'https://api.whatsapp.com'
    }
  },
  computed: {
    ...mapState({
      loading: state => state.loading,
      shop: state => state.shop,
      invoice: state => state.order.invoice,
    }),
    isPaymentType: function() {
      let paymentType = this.invoice.transaction.payment_type 
      if(paymentType == 'DIRECT' || paymentType == 'BANK_TRANSFER') {
        return 'DirectPayment'
      }
      return 'PaymentGateway'
    },
  },
  created() {
    if(!this.invoice){
      this.getOrder()
    } else {
      if(this.invoice.order_ref != this.$route.params.order_ref) {
        this.getOrder()
      } else {
        this.ready = true
        this.checkOrderStatus()
      }
    }
  },
  mounted() {
    if(this.$q.platform.is.desktop) {
        this.whatsappUrl = 'https://web.whatsapp.com'
      }
  },
  methods: {
    ...mapActions('order', ['getOrderById']),
    statusColor(status) {
      if(status == 'UNPAID') return 'bg-grey-7'
      if(status == 'CANCELED') return 'bg-red-6'
      if(status == 'COMPLETE') return 'bg-green-6'
      return 'bg-blue-7'
    },
    printInvoice() {
      const today = new Date().toDateString()
      document.title = `INVOICE #${this.invoice.order_ref} ${today}`
      this.isPrintPacking = false
      this.isPrintInvoice = true
      setTimeout(() => {
        window.print()
      }, 200)
    },
    printPacking() {
      const today = new Date().toDateString()
      document.title = `PACKING #${this.invoice.order_ref} ${today}`
      this.isPrintPacking = true
      this.isPrintInvoice = false
      setTimeout(() => {
        window.print()
      }, 200)
    },
    getOrder() {
      this.$store.commit('SET_LOADING', true)
      if(this.$route.params.order_ref) {
        this.getOrderById(this.$route.params.order_ref).then(response => {
          if(response.status == 200) {
            this.$store.commit('order/SET_INVOICE', response.data.results)
          }
          this.$store.commit('SET_LOADING', false)
          this.checkOrderStatus()
        }).catch(() => {
          this.$router.push({name: 'Cart'})
        })
      } else {
        this.$router.push({name: 'Cart'})
      }
    },
    money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },
    chatToAdmin() {
      let text = `Halo ${this.shop.name},\nMohon pesanan saya untuk segera di proses.\nTerima Kasih.\n\nReferensi Order:\n${location.href}`
      let url = `${this.whatsappUrl}/send?phone=${this.formatPhoneNumber(this.shop.phone)}&text=${encodeURI(text)}`

      window.open(url, '_blank')
    },
    kirimBuktiTransfer() {
      this.modalPayment = false
      let text = `Halo kak, saya akan mengkonfirmasi pembayaran atas pesanan\nInvoice ID: *${this.invoice.order_ref}*\nReferensi Order:\n${location.href}\n`
      let url = `${this.whatsappUrl}/send?phone=${this.formatPhoneNumber(this.shop.phone)}&text=${encodeURI(text)}`

      window.open(url, '_blank')
    },
    formatPhoneNumber(number) {
      let formatted = number.replace(/\D/g,'')

      if(formatted.startsWith('0')) {
        formatted = '62' + formatted.substr(1)
      }

      return formatted;
    },
    copy(str) {
      copyToClipboard(str)
      .then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil menyalin nomor rekening'
        })
      })
      .catch(() => {
        this.$q.notify({
          type: 'negative',
          message: 'Browser anda tidak support copy to clipboard'
        })
      })
    },
    handlePaymentModal() {
      this.modalPayment = true
    },
    getCheckOrder() {
       this.getOrderById(this.$route.params.order_ref).then(response => {
          if(response.status == 200) {
            this.$store.commit('order/SET_INVOICE', response.data.results)
            this.checkOrderStatus()
          }
        })
    },
    checkOrderStatus() {
      if(this.invoice.order_status == 'UNPAID' || this.invoice.order_status == 'PROCESS') {
        this.timeout = setTimeout(() => {
          this.getCheckOrder()
        }, 20000)
      } else {
        clearTimeout(this.timeout)
      }
    }
  },
  beforeDestroy() {
    clearTimeout(this.timeout)
  }

}
</script>

<style lang="scss">
.table-order-item {
  width: 100%;
  border-spacing: inherit;
  tr {
    th, td {
      padding:.5rem;
    }
    th {
      background-color: #666;  
      color: #ccc;
    }
    td {
      border-bottom: 1px solid #eee;
    }
  }
}
.print-packing,
.print-invoice {
  display:none;
}

@media print {
  .no-print {
    display: none;
  }
  .print-packing,
    .print-invoice {
      display:block;
    }
  .border {
     border: 1px solid #eee;
  }
  .table-order-item {
    width: 100%;
    border-spacing: inherit;
    tr {
      th, td {
        padding: none;
        border: 1px solid #eee;
      }
    }
  }
}
</style>