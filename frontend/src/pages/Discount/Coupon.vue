<template>
  <div>
    <div class="row items-center justify-between q-px-sm">
      <div class="text-h6">Kupon Diskon</div>
      <q-btn @click="handleAdd" unelevated color="primary">
        <q-icon name="add"></q-icon>
        <span>Kupon</span>
      </q-btn>
    </div>
    <div class="q-py-md">
     <q-list separator>
       <q-item>
         <q-item-section side>#</q-item-section>
         <q-item-section>Label</q-item-section>
         <q-item-section>Kupon Kode</q-item-section>
         <q-item-section>Diskon</q-item-section>
         <q-item-section side>Action</q-item-section>
       </q-item>
       <q-item v-for="(item, index) in coupons" :key="index">
         <q-item-section side>{{ index+1 }}</q-item-section>
         <q-item-section>{{ item.label }}</q-item-section>
         <q-item-section>{{ item.code }}</q-item-section>
         <q-item-section v-if="item.discount">
           <q-item-label v-if="item.discount.unit == 'percent'">
             {{ item.discount.value }} %
           </q-item-label>
           <q-item-label v-else>
             {{ moneyIDR(item.discount.value) }}
           </q-item-label>
         </q-item-section>
         <q-item-section side>
           <div class="q-gutter-sm">
            <q-btn size="sm" @click="handleDelete(item.id)" unelevated color="red" icon="delete" round></q-btn>
            <q-btn size="sm" @click="handleEdit(item)" unelevated color="blue" icon="edit" round></q-btn>
           </div>
         </q-item-section>
       </q-item>
     </q-list>
    </div>
    <q-dialog v-model="modal" persistent>
      <q-card class="card-medium">
        <div class="card-heading">{{ formType }} Kupon</div>
        <q-form @submit.prevent="submit">
          <q-card-section class="q-gutter-y-sm">
            <q-input required filled square label="Label" v-model="form.label" :rules="[val => !!val || 'Field is required']"></q-input>
            <q-input required filled square label="Kode Kupon" v-model="form.code" :rules="[val => !!val || 'Field is required']">
              <template v-slot:append>
                <q-btn label="Generate" @click="generateCoupon" color="primary" unelevated></q-btn>
              </template>
            </q-input>
            <q-select required filled square 
            label="Diskon" 
            v-model="form.discount_id" 
            :options="discountOptions"
             emit-value
             map-options
             :rules="[val => !!val || 'Field is required']"
             ></q-select>
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
  name: 'CouponBlock',
  data() {
    return {
      modal: false,
      form: {
        discount_id: '',
        code: '',
        label: ''
      },
      selectedData: null,
      formType: 'Tambah'
    }
  },
  computed: {
    ...mapState({
      discounts: state => state.discount.discounts,
      coupons: state => state.coupon.coupons,
    }),
    discountOptions() {
      let opts = []
       this.discounts.map(el => {
        opts.push({value: el.id, label: el.label})
      })
      return opts
    }
  },
  methods: {
    ...mapActions('discount', [
      'getDiscounts'
    ]),
    ...mapActions('coupon', [
      'getCoupons', 'storeCoupon', 'updateCoupon', 'deleteCoupon'
    ]),
    handleAdd() {
      if(!this.discounts.length) {
        return this.$q.notify({
          type: 'warning',
          message: 'Silahkan tambahkan diskon terlebih dahulu untuk membuat kupon'
        })
      }
      this.clearForm()
      this.formType = 'Tambah'
      this.modal = true
    },
    handleEdit(item) {
      this.clearForm()
      this.formType = 'Edit'
      this.selectedData = item
      this.setInputData()
      this.modal = true
    },
    handleDelete(id) {
      this.$q.dialog({
        message: 'Yakin akan menghapus ini?',
        cancel: true
      }).onOk(() => {
        this.deleteCoupon(id)
      })
    },
    setInputData() {
      this.form.label = this.selectedData.label
      this.form.code = this.selectedData.code
      this.form.discount_id = this.selectedData.discount_id
    },
    submit() {
      let formdata = this.form

      if(this.formType == 'Edit') {
        formdata._method = 'PUT'
        formdata.discount_id = this.selectedData.id
        this.updateCoupon(formdata).then(() => {
          this.closeModal()
        })
      }
      if(this.formType == 'Tambah') {
        this.storeCoupon(formdata).then(() => {
          this.closeModal()
        })
      }
    },
    closeModal() {
      this.clearForm()
      this.modal = false
    },
    clearForm() {
      this.discount_id = ''
      this.code = ''
      this.label = ''
    },
    generateCoupon() {
      let randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

      let result = '';
        for ( var i = 0; i < 8; i++ ) {
            result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }

      let hasData = this.coupons.filter(kp => kp.code == result)

      if(hasData.length) {
        this.generateCoupon()
      } else {
        this.form.code = result
      }
    },
  },
  mounted() {
    this.getCoupons()
  }
}
</script>

<style>

</style>