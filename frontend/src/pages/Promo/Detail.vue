<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'PromoIndex', query: { q: 'promo'}}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title v-if="promo">
         Produk Promo 
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <div class="q-pa-md">
      <div class="q-mb-sm" v-if="promo">
        <div class="text-lg text-weight-bold">{{ promo.label }}</div>
      </div>
      <div class="" v-if="promo">
        <table class="table full-width bordered">
          <tr>
            <th align="left">Label</th>
            <th align="right">{{ promo.label }}</th>
          </tr>
          <tr>
            <th align="left">Start</th>
            <td align="right">{{ promo.start}}</td>
          </tr>
          <tr>
            <th align="left">Selesai</th>
            <td align="right"><div>{{ promo.end}}</div><div class="text-grey-7 text-caption">( {{ promo.diff_end }} )</div></td>
          </tr>
          <tr>
            <th align="left">Status</th>
            <td align="right"><span class="q-px-md q-py-xs rounded-borders text-white" :class="promo.is_active ? 'bg-green' : 'bg-grey-7'">{{ promo.is_active ? 'Active' : 'Inactive' }}</span></td>
          </tr>
        </table>
      </div>
    </div>
     <div v-if="promo" class="q-mt-lg">
      <div class="row items-center justify-between q-pa-md">
         <div class="text-md">Produk Total {{ products.length }}</div>
        <div class="row items-center q-gutter-x-sm">
          <q-btn @click="handleAddProductPromo" unelevated color="accent">
            <q-icon name="add"></q-icon>
            <span>Produk</span>
          </q-btn> 
        </div>
      </div>
      <div class="q-mt-md">
        <q-list separator v-if="products.length">
          <q-item>
            <q-item-section side>#</q-item-section>
            <q-item-section>Nama</q-item-section>
            <q-item-section>Diskon</q-item-section>
            <q-item-section side>Actions</q-item-section>
          </q-item>
          <q-item v-for="(product, index) in products" :key="index">
            <q-item-section side>{{ index+1 }}</q-item-section>
            <q-item-section>{{ product.title }}</q-item-section>
            <q-item-section>{{ product.pivot.discount_type == 'PERCENT' ? product.pivot.discount_amount + ' %' : moneyIDR(product.pivot.discount_amount) }}</q-item-section>
            <q-item-section side>
                <q-btn 
                icon="delete" 
                padding="4px"
                size="sm"
                unelevated 
                color="red" 
                :disabled="syncLoading"
                @click="handleRemoveProductPromo(product.id)"></q-btn>
            </q-item-section>
          </q-item>
        </q-list>
      </div>
    </div>
    <q-dialog v-model="searchModal" persistent>
      <q-card class="card-medium">
        <div class="flex justify-between items-center card-heading">
          <div class=""> Produk Promo</div>
          <q-btn icon="close" v-close-popup round padding="0px" flat></q-btn>
        </div>
          <q-card-section class="q-gutter-y-sm" style="min-height:150px;max-height:400px;overflow-y:auto;">
            <div v-if="productSearch.length">
              <q-list separator>
                <q-item v-for="product in productSearch" :key="product.id" clickable @click="syncProduct(product)">
                  <q-item-section side>
                    <q-btn 
                    icon="check_box" 
                    padding="0px"
                    unelevated 
                    size="12px"
                    ></q-btn>
                  </q-item-section>
                  <q-item-section>{{ product.title }}</q-item-section>
                </q-item>
              </q-list>
            </div>
            <q-inner-loading :showing="isLoading"></q-inner-loading>
            <div v-if="!isLoading && !productSearch.length" class="text-center q-pt-lg">Tidak ada data ditemukan</div>
          </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="productPromoModal" persistent>
      <q-card class="card-medium" v-if="productSelected ">
        <q-card-section>
          <div class="text-md text-weight-bold q-mb-md">Produk {{ productSelected.title }}</div>
          <q-form @submit.prevent="submitProductPromo">
              <div class="flex items-center q-mt-sm full-width q-gutter-x-sm">
                <q-input class="col" required filled square label="Diskon Nominal" v-model="form.discount_amount" :rules="[val => !!val || 'Field is required']"></q-input>
                <q-select required filled square 
                label="Diskon Tipe"
                class="col"
                v-model="form.discount_type" 
                :options="discountTypeOptions"
                emit-value
                map-options
                :rules="[val => !!val || 'Field is required']"
                ></q-select>
              </div>
              <div class="flex justify-end q-gutter-x-sm q-mt-md">
                <q-btn v-close-popup label="Close" outline color="primary"></q-btn>
                <q-btn type="submit" label="Submit" unelevated color="primary"></q-btn>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'ProductPromo',
  data() {
    return {
      productPromoModal: false,
      productSelected: null,
      product_ids: [],
      promo: null,
      isLoading: false,
      syncLoading: false,
      searchModal: false,
      search: '',
      productSearch: [],
      products: [],
      form: {
        promo_id: null,
        product_id: null,
        discount_amount: 0,
        discount_type: 'PERCENT'
      },
      discountTypeOptions: [
        { value: 'PERCENT', label: 'Persen'},
        { value: 'AMOUNT', label: 'Nominal'},
      ],
      removeId: null
    }
  },
  methods: {
    getPromoDetail() {
      Api().get('getPromoDetail/'+ this.$route.params.id).then(response => {
       if(response.status == 200) {
          this.products = response.data.results.products
          this.promo = response.data.results.promo
        }
      })
    },
    getProductPromo() {
      Api().get('getProductPromo/'+ this.$route.params.id).then(response => {
       if(response.status == 200) {
          this.products = response.data.results
        }
      })
      
    },
    findProduct() {
      this.isLoading = true
      this.productSearch = []
      Api().get('findProductWithoutPromo').then(response => {
        if(response.status == 200) {
          this.productSearch = response.data.results
        }
      })
      .finally(() => {
        this.isLoading = false
      })
    },
    handleAddProductPromo() {
      this.search = ''
      this.searchModal = true
      this.productSearch = []
      this.findProduct()
    },
    syncProduct(item) {
      this.productSelected = item
      this.form.product_id =  item.id
      this.form.promo_id =  this.promo.id

      this.searchModal = false
      this.productPromoModal = true
    },
    submitProductPromo() {
      if(this.form.discount_type == 'PERCENT') {
        if(this.form.discount_amount == 0 || this.form.discount_amount >= 99) {
          this.$q.notify({
            type: 'negative',
            message: 'Tentukan nilai nominal diskon dengan benar'
          })

          return
        }
      }
      Api().post('submitProductPromo', this.form).then(() => {
        this.getProductPromo()
      }).finally(() => this.productPromoModal = false)
    },
    handleRemoveProductPromo(id) {
      this.removeId = id
      this.$q.dialog({
        title: 'Yakin akan menghapus data?',
        cancel: true
      })
      .onOk(() => {
        this.removeProductPromo()
      })
    },
    removeProductPromo() {
      Api().post('removeProductPromo', { promo_id: this.promo.id, product_id: this.removeId })
      .then(() => this.getProductPromo())
    }
  },
  mounted() {
    this.getPromoDetail()
  }
}
</script>

<style>

</style>