<template>
  <div>
    <div class="row items-center justify-between q-px-sm">
      <div class="text-h6">Master Diskon</div>
      <q-btn @click="handleAddDiscount" unelevated color="primary">
        <q-icon name="add"></q-icon>
        <span>Diskon</span>
      </q-btn>
    </div>
    <div class="q-py-md">
     <q-list separator>
       <q-item>
         <q-item-section side>#</q-item-section>
         <q-item-section>Label</q-item-section>
         <q-item-section>Nilai</q-item-section>
         <q-item-section side>Action</q-item-section>
       </q-item>
       <q-item v-for="(item, index) in discounts" :key="index">
         <q-item-section side>{{ index+1 }}</q-item-section>
         <q-item-section>{{ item.label }}</q-item-section>
         <q-item-section>
           <q-item-label v-if="item.unit == 'percent'">
             {{ item.value }} %
           </q-item-label>
           <q-item-label v-else>
             {{ moneyIDR(item.value) }}
           </q-item-label>
         </q-item-section>
         <q-item-section side>
           <div class="q-gutter-sm">
            <q-btn size="sm" @click="handleDeleteDiscount(item.id)" unelevated color="red" icon="delete" round></q-btn>
            <q-btn size="sm" @click="handleEditDiscount(item)" unelevated color="blue" icon="edit" round></q-btn>
           </div>
         </q-item-section>
       </q-item>
     </q-list>
    </div>
    <q-dialog v-model="modal" persistent>
      <q-card class="card-medium">
        <div class="card-heading">{{ formType }} Diskon</div>
        <q-form @submit.prevent="submit">
          <q-card-section>
            <q-input required filled square label="Label" v-model="form.label" :rules="[val => !!val || 'Field is required']"></q-input>
            <div class="flex items-center q-mt-sm">
              <q-input required filled square label="Amount" v-model="form.value" :rules="[val => !!val || 'Field is required']"></q-input>
              <q-select required filled square 
              label="Unit"
               v-model="form.unit" 
               :options="options"
               emit-value
               map-options
               :rules="[val => !!val || 'Field is required']"
               ></q-select>
            </div>
            <div class="flex items-center justify-end q-mt-md q-gutter-x-sm">
              <q-btn flat color="primary" label="Batal" v-close-popup></q-btn>
              <q-btn unelevated color="primary" type="submit" label="Simpan"></q-btn>
            </div>
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'DiscountBlock',
  data() {
    return {
      modal: false,
      options: [
        {value: 'percent', label: 'Percent'},
        {value: 'amount', label: 'Amount'},
      ],
      form: {
        label: '',
        unit: 'percent',
        value: 0
      },
      selectedDiscount: null,
      formType: 'Tambah'
    }
  },
  computed: {
     ...mapState({
      discounts: state => state.discount.discounts,
      coupons: state => state.coupon.coupons,
      promotes: state => state.promo.promotes
    }),
  },
  methods: {
    ...mapActions('discount', [
      'getDiscounts', 'storeDiscount', 'updateDiscount', 'deleteDiscount'
    ]),
    handleAddDiscount() {
      this.clearForm()
      this.formType = 'Tambah'
      this.modal = true
    },
    handleEditDiscount(item) {
      this.clearForm()
      this.formType = 'Edit'
      this.selectedDiscount = item
      this.setInputData()
      this.modal = true
    },
    handleDeleteDiscount(id) {
      let hasCoupon = this.coupons.filter(cp => cp.discount_id == id);
      if(hasCoupon.length) {
        this.$q.notify({
          type: 'negative',
          message: 'Gagal! Diskon terasosiasi dengan kupon'
        })
        return
      }
      let hasPromoted = this.promotes.filter(cp => cp.discount_id == id);
      if(hasPromoted.length) {
        this.$q.notify({
          type: 'negative',
          message: 'Gagal! Diskon terasosiasi dengan promo'
        })
        return
      }
      this.$q.dialog({
        message: 'Yakin akan menghapus ini?',
        cancel: true
      }).onOk(() => {
        this.deleteDiscount(id)
      })
    },
    setInputData() {
      this.form.label = this.selectedDiscount.label
      this.form.unit = this.selectedDiscount.unit
      this.form.value = this.selectedDiscount.value
    },
    submit() {
      let formdata = this.form

      if(this.formType == 'Edit') {
        formdata._method = 'PUT'
        formdata.id = this.selectedDiscount.id
        this.updateDiscount(formdata).then(() => {
          this.closeModal()
        })
      }
      if(this.formType == 'Tambah') {
        this.storeDiscount(formdata).then(() => {
          this.closeModal()
        })
      }
    },
    closeModal() {
      this.clearForm()
      this.modal = false
    },
    clearForm() {
      this.form.label = ''
      this.form.unit = 'percent'
      this.form.value = 0
    }
  },
  mounted() {
    this.getDiscounts()
  }
}
</script>

<style>

</style>