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
              <div class="text-md text-weight-bold text-primary">{{  transaction.payment_type.split('_').join(' ') }}</div>
              <div class="text-grey-7 q-mt-sm" style="font-size:13.5px;">
              Lakukan pembayaran sebelum melewati batas pembayaran, Pastikan nominal pembayaran sesuai dengan tagihan, Pembayaran akan di <b>verifikasi otomatis oleh sistem.</b> 
            </div>
            </div>     
          </div>
        </q-card-section>
      </q-card>
      <q-card square class="shadow">
        <div class="card-heading">Informasi Pembayaran</div>
        <q-card-section>
           <div class="q-gutter-y-sm">
             <template v-if="!isOVO">
              <div class="column justify-center items-center q-gutter-y-sm" v-if="transaction.qr_url">
                <div>QR CODE {{ transaction.payment_name  }}</div>
                <img :src="transaction.qr_url"  style="width:120px;height:auto;"/>
                <div>
                  <q-btn @click="viewQrModal = true" unelevated color="primary" size="12px" text-color="white" label="Perbesar"></q-btn>
                </div>
              </div>
               <div v-if="transaction.payment_code">
                <div>Kode Bayar</div>
                <div class="row justify-between">
                  <div class="text-lg text-weight-bold text-primary">{{ transaction.payment_code }}</div>
                  <div>
                    <q-btn @click="copy" unelevated color="primary" size="12px" text-color="white" label="Salin Nomor"></q-btn>
                  </div>
                </div>
               </div>
             </template>
             <template v-if="isOVO">
               <div class="text-lg text-weight-bold text-primary">{{ transaction.payment_name }}</div>
               <div class="text-grey-7">( Lihat panduan pembayaran )</div>
             </template>
          </div>
            <div class=" q-gutter-y-xs q-mt-lg">
              <div>Batas Pembayaran</div>
              <div class="text-weight-medium">{{ formatCountDown(transaction.expired_time) }}</div>
           </div>
        </q-card-section>
      </q-card>

      <div v-if="transaction" class="bg-white">
        <div class="card-heading">Panduan Pembayaran</div>
        <q-list bordered class="text-grey-9">
          <template v-for="(item, index) in instructions">
            <q-expansion-item
            :key="index"
              group="somegroup"
              :label="item.title"
              :default-opened="index == 0"
              header-class="bg-grey-7 text-white"
              expand-icon-class="text-white"
            >
              <q-card class="q-py-md q-pb-xl">
                 <q-list bordered>
                  <q-item v-for="(step, index) in item.steps" :key="index">
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
      </div>
    </div>
    <q-dialog v-model="viewQrModal" persistent maximized >
      <q-card flat class="max-width">
        <div class="row justify-between items-center box-shadow q-pa-md">
          <div class="text-weight-medium text-md">Scan atau download Qr Code</div>
          <q-btn icon="close" flat round v-close-popup></q-btn>
        </div>
        <q-card-section class="flex justify-center items-center q-pt-lg">
          <img :src="transaction.qr_url"  style="max-width:380px;width:100%;height:auto;"/>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { copyToClipboard } from 'quasar'
export default {
  name: 'PaymentGateway',
  props: ['transaction'],
  data () {
    return {
      viewQrModal: false
    }
  },
  computed: {
    isOVO() {
      return this.transaction.payment_method == 'OVO'
    },
    isQRIS() {
      return this.transaction.payment_method == 'QRIS'
    },
    instructions: function() {
      let ins = JSON.parse(this.transaction.instructions)
      if(this.isOVO) {
        ins[0].steps = [
          'Periksa <b>aplikasi OVO</b> di Ponsel Anda', 
          'Akan muncul prompt transaksi. Pastikan data transaksi sudah sesuai', 
          'Klik tombol <b>Bayar</b>',
          'Transaksi selesai. Simpan bukti pembayaran Anda'
        ]
      } 
      return ins

    }
  },
  methods: {
    copy() {
      copyToClipboard(this.transaction.payment_code)
      .then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil menyalin kode bayar'
        })
      })
      .catch(() => {
        // fail
      })
    },
    formatCountDown(timestamp) {
      var date = new Date(timestamp * 1000);
      // return date.toLocaleString('id-ID');
      return new Intl.DateTimeFormat('id-ID', { dateStyle: 'full', timeStyle: 'long' }).format(date);

    },
    money(number) {
     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR'}).format(number)
    },
  }
}
</script>

<style>

</style>