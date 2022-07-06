<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Promo
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <div class="q-pa-sm">
      <div class="row items-center justify-between q-px-sm">
        <div class="text-h6">Promo Diskon</div>
        <div class="row items-center q-gutter-x-sm">
          <q-btn @click="handleAdd" unelevated color="primary">
            <q-icon name="add"></q-icon>
            <span>Promo</span>
          </q-btn>
        </div>
      </div>
    </div>
     <div class="q-py-md">
        <q-list separator>
          <q-item>
            <q-item-section side>#</q-item-section>
            <q-item-section>Label / Produk Total</q-item-section>
            <q-item-section>Status</q-item-section>
            <q-item-section side>Action</q-item-section>
          </q-item>

          <q-item v-for="(item, index) in promos" :key="index">
            <q-item-section side>{{ index+1 }}</q-item-section>
            <q-item-section>
              <q-item-label><span>{{ item.label }}</span> <span class="q-ml-sm">({{ item.products_count }})</span></q-item-label>
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
                <q-btn size="sm" @click="handleEdit(item)" unelevated color="teal" icon="edit_calendar" round></q-btn>
                <q-btn size="sm" @click="$router.push({name: 'PromoDetail', params: { id: item.id }})" unelevated color="blue" icon="edit_note" round></q-btn>
              </div>
            </q-item-section>
          </q-item>
          
          <q-item v-if="!promos.length">
            <q-item-section class="text-center">
              Tidak ada data
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
              <q-input label="Start Date" filled v-model="form.start_date" readonly :rules="[val => !!val || 'Field is required']">
                <template v-slot:append>
                   <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="form.start_date" mask="YYYY-MM-DD HH:mm">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
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
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="form.end_date" mask="YYYY-MM-DD HH:mm">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
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
                <q-btn outline color="primary" label="Batal" v-close-popup></q-btn>
                <q-btn unelevated color="primary" type="submit" label="Simpan"></q-btn>
              </div>
            </q-card-section>
          </q-form>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'PromoIndex',
  data() {
    return {
     modal: false,
      form: {
        id: '',
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
      products: state => state.promo.products,
      promos: state => state.promo.promos,
    }),
    isDesktop() {
      return window.innerWidth > 600 ? true : false
    }
  },
  methods: {
    ...mapActions('promo', [
      'getPromos', 'storePromo', 'updatePromo', 'deletePromo', 'getProductPromo'
    ]),
    handleAdd() {
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
        title: 'Yakin akan menghapus ini?',
        cancel: true
      }).onOk(() => {
        this.deletePromo(id)
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
        this.updatePromo(formdata).then(() => {
          this.closeModal()
        })
      }
      if(this.formType == 'Tambah') {
        this.storePromo(formdata).then(() => {
          this.closeModal()
        })
      }
    },
    searchProduct(evt) {
      // console.log(evt);
    },  
    closeModal() {
      this.clearForm()
      this.modal = false
    },
    clearForm() {
      this.form.id = ''
      this.form.label = ''
      this.form.start_date = ''
      this.form.end_date = ''
    },
  },
  mounted() {
    this.getPromos()
  }
}
</script>
