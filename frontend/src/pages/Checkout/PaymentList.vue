<template>
  <div class="q-gutter-y-lg">
    <div v-if="payments.localbanks.length">
      <fieldset>
        <legend class="q-px-sm">Bank Transfer</legend>
        <div class="row q-gutter-sm payment-container">
          <div 
            class="box-shadow cursor-pointer payment-list bank_trf" 
            :class="{'is-selected' : isSelectedBank(item.id)}"
            v-for="(item, index) in payments.localbanks" 
            :key="index" @click="selectPaymentBank(item)">
            <div>
              <div class="text-center text-weight-bold text-md">{{ item.bank_name }}</div>
              <div class="text-weight-medium">Bank Transfer</div>
            </div>
          </div>
        </div>
        <div class="q-pa-sm text-caption">*Verifikasi manual</div>
      </fieldset>
    </div>
    <div v-if="virtualAccounts.length">
       <fieldset>
        <legend class="q-px-sm">Virtual Account</legend>
        <div class="row q-gutter-sm payment-container">
          <div v-for="(item, index) in virtualAccounts" :key="index">
            <div 
              class="box-shadow cursor-pointer payment-list" 
              :class="{'is-selected' : isSelected(item.code)}"
               @click="selectPayment(item)">
                <div class="image">
                  <img v-if="item.icon_url" :src="item.icon_url" />
                  <img v-else src="/static/no-image.png" />
                </div>
                <div class="text-center name">{{ item.name }}</div>
              </div>
            <div v-if="isFeeCustomer(item.fee_customer)" class="text-grey-7 text-xs q-pa-xs text-center">Fee {{ moneyIDR(calculateFee(item.fee_customer)) }}</div>
          </div>
        </div>
      </fieldset>
    </div>
    <div v-if="convenienceStore.length">
      <fieldset>
        <legend class="q-px-sm">Convenion Store</legend>
         <div class="row q-gutter-sm payment-container">
           <div v-for="(item, index) in convenienceStore" 
            :key="index">
            <div 
            class="box-shadow cursor-pointer payment-list" 
            :class="{'is-selected' : isSelected(item.code)}"
             @click="selectPayment(item)">
              <div class="image">
                  <img v-if="item.icon_url" :src="item.icon_url" />
                  <img v-else src="/static/no-image.png" />
                </div>
              <div class="text-center name">{{ item.name }}</div>
            </div>
            <div v-if="isFeeCustomer(item.fee_customer)" class="text-grey-7 text-xs q-pa-xs text-center">Fee {{ moneyIDR(calculateFee(item.fee_customer)) }}</div>
           </div>
        </div>
      </fieldset>
    </div>
    <div v-if="ewalet.length">
      <fieldset>
        <legend class="q-px-sm">E-Walet</legend>
         <div class="row q-gutter-sm payment-container">
           <div v-for="(item, index) in ewalet" :key="index">
            <div 
              class="box-shadow cursor-pointer payment-list" 
              :class="{'is-selected' : isSelected(item.code)}"
               @click="selectPayment(item)">
                <div class="image">
                  <img v-if="item.icon_url" :src="item.icon_url" />
                  <img v-else src="/static/no-image.png" />
                </div>
                <div class="text-center name">{{ item.name }}</div>
              </div>
              <div v-if="isFeeCustomer(item.fee_customer)" class="text-grey-7 text-xs q-pa-xs text-center">Fee {{ moneyIDR(calculateFee(item.fee_customer)) }}</div>
           </div>
         </div>
      </fieldset>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    payments: Object,
    isCod: Boolean,
    paymentSelected: Object
  },
  computed: {
    virtualAccounts() {
      if(this.payments && this.payments.paymentGateway.length) {
        return this.payments.paymentGateway.filter(function(el) {
          return el.group == 'Virtual Account' && el.active == true
        })
      }
      return []
    },
    convenienceStore() {
      if(this.payments && this.payments.paymentGateway.length) {
        return this.payments.paymentGateway.filter(function(el) {
          return el.group == 'Convenience Store' && el.active == true
        })
      }
      return []
    },
    ewalet() {
      if(this.payments && this.payments.paymentGateway.length) {
        return this.payments.paymentGateway.filter(function(el) {
          return el.group == 'E-Wallet' && el.active == true
        })
      }
      return []
    },
    isSelectedCod() {
      if(this.paymentSelected) {
        if(this.paymentSelected.payment_type == 'COD') {
          return true
        } else {
          return false
        }
      }else {
        return false
      }
    },
    formOrder() {
      return this.$store.getters['order/getFormOrder']
    }
  },
  methods: {
    isFeeCustomer(fee) {
      if(fee.flat > 0 || fee.percent > 0) {
        return true
      }
      return false
    },
    calculateFee(fee) {
      let totalFee = parseInt(fee.flat);
      if(fee.percent > 0) {
        let feePercent = (parseInt(this.formOrder.total) * parseFloat(fee.percent))/100

        if(fee.flat > 0) {
          totalFee += feePercent
        } else {
          totalFee = feePercent
        }
         
      }
      return parseInt(Math.ceil(totalFee));
    },
    isSelected(code) {
      if(this.paymentSelected) {
        if(this.paymentSelected.code == code) {
          return true
        } else {
          return false
        }
      }else {
        return false
      }
    },
    isSelectedBank(id) {
      if(this.paymentSelected) {
        if(this.paymentSelected.id == id) {
          return true
        } else {
          return false
        }
      }else {
        return false
      }
    },
    selectCodPayment() {
      let cod = {
        payment_type: 'COD',
        payment_method: 'COD',
        payment_name: 'Bayar Ditempat' 
      }
      this.commitFormOrder(cod)
    },
    selectPayment(item) {
      this.commitFormOrder({...item, payment_type: 'PAYMENT_GATEWAY', payment_fee: this.calculateFee(item.fee_customer)})

    },
    selectPaymentBank(item){
      this.commitFormOrder({...item, payment_type: 'BANK_TRANSFER'})
    },
    commitFormOrder(item) {

      this.$emit('onSelectPayment', item)

      let formData = {
        payment_method: '',
        payment_name: '',
        payment_code: '',
        payment_type: '',
        payment_fee: 0,
      }

      if(item.payment_fee) {
        formData.payment_fee = parseInt(item.payment_fee)
      }

      if(item.payment_type == 'BANK_TRANSFER') {

        formData.payment_method = 'BANK_TRANSFER'
        formData.payment_name = `${item.bank_name} ${item.bank_office} - a/n ${item.account_name}`
        formData.payment_code = item.account_number
        formData.payment_type = item.payment_type

      }
      if(item.payment_type == 'PAYMENT_GATEWAY') {
        formData.payment_method = item.code
        formData.payment_name = item.name
        formData.payment_code = ''
        formData.payment_type = item.payment_type
      }
      if(item.payment_type == 'COD') {
        formData.payment_method = item.payment_method
        formData.payment_name = item.payment_name
        formData.payment_code = 'COD'
        formData.payment_type = 'COD'
      }

      for(let x in formData) {
        this.$store.commit('order/SET_FORM_ORDER', { key: x, value: formData[x]})
      }

      this.$emit('checkStep')
 
    }
  },
}
</script>