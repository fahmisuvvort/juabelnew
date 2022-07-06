<template>
  <div>
    <div class="row items-center justify-between q-px-sm">
      <div class="text-h6">Promo Diskon</div>
      <div class="row items-center q-gutter-x-sm">
        <q-btn @click="handleAdd" unelevated color="primary">
          <q-icon name="add"></q-icon>
          <span>Promo</span>
        </q-btn>
      </div>
    </div>
    <div class="q-py-md">
     <q-list separator>
       <q-item>
         <q-item-section side>#</q-item-section>
         <q-item-section>Label</q-item-section>
         <q-item-section v-if="isDesktop">Diskon</q-item-section>
         <q-item-section>Status</q-item-section>
         <q-item-section side>Action</q-item-section>
       </q-item>
       <q-item v-for="(item, index) in promotes" :key="index">
         <q-item-section side>{{ index+1 }}</q-item-section>
         <q-item-section>
           <q-item-label>{{ item.label }} [{{ item.products_count}}]</q-item-label>
           <div v-if="!isDesktop">
            <q-chip square text-color="white" color="teal" size="sm">{{ item.discount.label }} </q-chip>
           </div>
         </q-item-section>
         <q-item-section v-if="isDesktop">
           <div>
            <q-chip square text-color="white" color="teal" size="sm">{{ item.discount.label }} </q-chip>
           </div>
         </q-item-section>
         <q-item-section>
           <q-item-label>
             <q-chip size="sm" text-color="white" :color="item.is_active? 'green' : 'grey'" :label="item.is_active ? 'Active' : 'Inactive'">
             </q-chip>
             </q-item-label>
         </q-item-section>
         <q-item-section side>
           <div class="q-gutter-sm">
            <q-btn size="sm" @click="handleDelete(item.id)" unelevated color="red" icon="delete" round></q-btn>
            <q-btn size="sm" @click="handleEdit(item)" unelevated color="blue" icon="edit" round></q-btn>
            <q-btn size="sm" @click="$router.push({name: 'PromoDetail', params: { id: item.id }})" unelevated color="teal" icon="settings" round></q-btn>
           </div>
         </q-item-section>
       </q-item>
     </q-list>
    </div>
    <div>
      <q-list>
        <q-item v-for="product in products" :key="product.id">
          <q-item-section>{{ product.title }}</q-item-section>
          <q-item-section side>
            
          </q-item-section>
        </q-item>
      </q-list>
    </div>
    <q-dialog v-model="modal" persistent>
      <q-card class="card-medium">
        <div class="card-heading">{{ formType }} Promo</div>
        <q-form @submit.prevent="submit">
          <q-card-section class="q-gutter-y-sm">
            <q-input required filled square label="Label" v-model="form.label" :rules="[val => !!val || 'Field is required']"></q-input>
            <q-select required filled square 
              label="Diskon" 
              v-model="form.discount_id" 
              :options="discountOptions"
              emit-value
              map-options
              :rules="[val => !!val || 'Field is required']"
             ></q-select>
            <q-input label="Start Date" filled v-model="form.start_date" readonly :rules="[val => !!val || 'Field is required']">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date v-model="form.start_date" mask="YYYY-MM-DD HH:mm">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template v-slot:append>
                <q-icon name="access_time" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-time v-model="form.start_date" mask="YYYY-MM-DD HH:mm" format24h>
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
            <q-input label="End Date" filled v-model="form.end_date" readonly :rules="[val => !!val || 'Field is required']">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date v-model="form.end_date" mask="YYYY-MM-DD HH:mm">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template v-slot:append>
                <q-icon name="access_time" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-time v-model="form.end_date" mask="YYYY-MM-DD HH:mm" format24h>
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
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
  name: 'PromoteBlock',
  data() {
    return {
      modal: false,
      form: {
        id: '',
        discount_id: '',
        start_date: '',
        end_date: '',
        label: ''
      },
      selectedData: null,
      formType: 'Tambah',
      search: '',
      productSearch: []
    }
  },
  computed: {
    ...mapState({
      discounts: state => state.discount.discounts,
      promotes: state => state.promo.promotes,
      products: state => state.promo.products
    }),
    discountOptions() {
      let opts = []
       this.discounts.map(el => {
        opts.push({value: el.id, label: el.label})
      })
      return opts
    },
    isDesktop() {
      return window.innerWidth > 600 ? true : false
    }
  },
  methods: {
    ...mapActions('discount', [
      'getDiscounts'
    ]),
    ...mapActions('promo', [
      'getPromotes', 'storePromote', 'updatePromote', 'deletePromote', 'getProducts'
    ]),
    handleAdd() {
      if(!this.discounts.length) {
        return this.$q.notify({
          type: 'warning',
          message: 'Silahkan tambahkan diskon terlebih dahulu untuk membuat promo'
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
        this.deletePromote(id)
      })
    },
    setInputData() {
      this.form.id = this.selectedData.id
      this.form.label = this.selectedData.label
      this.form.start_date = this.selectedData.start_date
      this.form.end_date = this.selectedData.end_date
      this.form.discount_id = this.selectedData.discount_id
    },
    submit() {
      let formdata = this.form

      if(this.formType == 'Edit') {
        formdata._method = 'PUT'
        this.updatePromote(formdata).then(() => {
          this.closeModal()
        })
      }
      if(this.formType == 'Tambah') {
        this.storePromote(formdata).then(() => {
          this.closeModal()
        })
      }
    },
    searchProduct(evt) {
      console.log(evt);
    },  
    closeModal() {
      this.clearForm()
      this.modal = false
    },
    clearForm() {
      this.form.id = ''
      this.form.discount_id = ''
      this.form.label = ''
      this.form.start_date = ''
      this.form.end_date = ''
    },
  },
  mounted() {
    this.getPromotes()
  }
}
</script>

<style>

</style>