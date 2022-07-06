<template>
  <div class="text-grey-9">
    <div class="q-gutter-y-lg" v-if="transaction">
      <q-card square class="shadow">
        <div class="q-py-md">
          <q-list>
            <q-item class="text-weight-bold" dense>
              <q-item-section >
                <div>Total Tagihan</div>
              </q-item-section>
              <q-item-section side>
                <div class="text-md">{{ moneyIDR(transaction.amount) }}</div>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </q-card>
      <q-card square class="shadow">
        <div class="card-heading">Metode Pembayaran</div>
        <q-card-section>
          <div class="">
            <div>
              <!-- <div class="text-md">Metode Pembayaran</div> -->
              <div class="text-md text-weight-bold text-primary">{{ transaction.payment_type.split('_').join(' ') }}</div>
              <div class="text-grey-7 q-mt-sm" style="font-size:13.5px;">
                Lakukan pembayaran sebelum melewati batas pembayaran, Pastikan nominal pembayaran sesuai dengan Tagihan, Konfirmasi pembayaran dengan megirim bukti transfer
            </div>
            </div>     
          </div>
        </q-card-section>
      </q-card>
      <q-card square class="shadow">
        <div class="card-heading">Informasi Pembayaran</div>
        <q-card-section>
           <div class="q-gutter-y-sm">
            <div>No. Rekening</div>
            <div class="row justify-between items-center">
              <div class="text-lg text-weight-bold text-primary">{{ transaction.payment_code }}</div>
              <div>
                <q-btn @click="copy" unelevated color="primary" size="12px" text-color="white" label="Salin No Rekening"></q-btn>
              </div>
            </div>
            <div class="text-sm text-grey-7" v-html="transaction.payment_name"></div>
          </div>
          <div class="q-gutter-y-xs q-mt-lg">
              <div>Batas Pembayaran</div>
              <div class="text-weight-medium text-xs">{{ expiredFormatDate }}</div>
           </div>
        </q-card-section>
      </q-card>

      <div class="bg-white">
        <div class="card-heading">Panduan Pembayaran</div>
        <q-list bordered class="text-grey-9">
          <template>
            <q-expansion-item
              group="somegroup"
              label="Bank Transfer"
              default-opened
              header-class="bg-grey-7 text-white"
              expand-icon-class="text-white"
            >
              <q-card class="q-py-md q-pb-xl">
                 <q-list bordered>
                  <q-item v-for="(step, index) in instructions" :key="step">
                    <q-item-section avatar>
                      <q-avatar color="grey-7" text-color="white" size="sm">
                        {{ index+1 }}
                      </q-avatar>
                    </q-item-section>

                    <q-item-section>
                      <q-item-label v-html="step"></q-item-label>
                    </q-item-section>
                  </q-item>
                 </q-list>
              </q-card>
            </q-expansion-item>
        </template>
      </q-list>
      <div class="sticky-bottom q-pa-md bg-white">
         <q-btn @click.prevent="kirimBuktiTransfer" unelevated no-caps label="Kirim Bukti Transfer Disini" class="full-width" color="primary" text-color="white" style="border-radius:none;"></q-btn>
      </div>
      </div>
    </div>
  </div>
</template>

<script>
import { copyToClipboard } from 'quasar'
export default {
  name: 'DirectPayment',
  props: ['transaction'],
  data () {
    return {
      modal: false,
      preview: '',
      fileData: ''
    }
  },
  computed: {
    instructions() {
      let str =[`Lakukan transfer ke nomor rekening <b>${ this.transaction.payment_code }.</b>`,
      `Transfer dengan total pembayaran  <b>${ this.priceFormated }</b><br> <small>( jangan dibulatkan )</small> `,
      "Simpan dan <b>kirim bukti pembayaran.</b>",
      `Kirim bukti transfer sebelum <b>${ this.expiredFormatDate } </b> agar pesananmu segera diproses.`]
      return str
    },
    priceFormated() {
      return this.moneyIDR(this.transaction.amount)
    },
    expiredFormatDate() {
      var date = new Date(this.transaction.expired_time * 1000);
      return new Intl.DateTimeFormat('id-ID', { dateStyle: 'full', timeStyle: 'long' }).format(date);
    },
  },
  methods: {
    copy() {
      copyToClipboard(this.transaction.payment_code)
      .then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil menyalin nomor rekening'
        })
      })
      .catch(() => {
        // fail
      })
    },
    money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },
    kirimBuktiTransfer() {
      this.$emit('kirimBukti')
    },
  }
}
</script>
