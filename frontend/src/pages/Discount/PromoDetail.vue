<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'DiscountIndex', query: { q: 'promo'}}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Produk Promo
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <div class="q-pa-md" v-if="promoted">
      <div class="row items-center justify-between">
        <div class="text-h6">{{ promoted.label }} <span> <q-chip size="sm" text-color="white" :color="promoted.is_active? 'green' : 'grey'" :label="promoted.is_active ? 'Active' : 'Inactive'">
             </q-chip></span></div>
        <div class="row items-center q-gutter-x-sm">
          <q-btn @click="handleAddProductPromoted" unelevated color="teal">
            <q-icon name="add"></q-icon>
            <span>Produk</span>
          </q-btn> 
        </div>
      </div>
    </div>
     <q-list separator v-if="promoted">
       <q-item>
         <q-item-section>Label / Diskon</q-item-section>
         <q-item-section>Waktu</q-item-section>
       </q-item>
       <q-item>
         <q-item-section>
           <q-item-label>{{ promoted.label }}</q-item-label>
           <div>
            <q-chip square text-color="white" color="green" size="sm">{{ promoted.discount.label }}</q-chip>
           </div>
         </q-item-section>
         <q-item-section>
           <q-item-label>Start: {{ promoted.start_date }}</q-item-label>
           <q-item-label>Selesai: {{ promoted.end_date }}</q-item-label>
         </q-item-section>
       </q-item>
     </q-list>
    <div class="q-pa-md text-md q-mt-lg">Produk Total {{ products.length }}</div>
    <q-list separator v-if="products.length">
      <q-item>
        <q-item-section side>#</q-item-section>
        <q-item-section>Nama</q-item-section>
        <q-item-section>Stok</q-item-section>
        <q-item-section side>Actions</q-item-section>
      </q-item>
      <q-item v-for="(product, index) in products" :key="index">
        <q-item-section side>{{ index+1 }}</q-item-section>
        <q-item-section>{{ product.title }}</q-item-section>
        <q-item-section>{{ product.variant_items_sum_item_stock? product.variant_items_sum_item_stock : product.stock }}</q-item-section>
        <q-item-section side>
            <q-btn 
            icon="delete" 
            padding="4px"
            size="sm"
            unelevated 
            color="red" 
            :disabled="syncLoading"
            @click="syncProduct(product.id)"></q-btn>
        </q-item-section>
      </q-item>
    </q-list>
    <q-dialog v-model="searchModal" persistent>
      <q-card class="card-medium">
        <div class="flex justify-between items-center card-heading">
          <div class=""> Produk Promo</div>
          <q-btn icon="close" v-close-popup round padding="0px" flat></q-btn>
        </div>
          <q-card-section class="q-gutter-y-sm" style="min-height:150px;max-height:400px;overflow-y:auto;">
            <div v-if="productSearch.length">
              <q-list>
                <q-item v-for="product in productSearch" :key="product.id">
                  <q-item-section>{{ product.title }}</q-item-section>
                  <q-item-section side>
                    <q-btn 
                    :icon="isSelected? 'check_box' : 'check_box_outline_blank'" 
                    padding="0px"
                    unelevated 
                    :color="isSelected(product.id) ? 'primary' : 'grey'" 
                    @click="syncProduct(product.id)"></q-btn>
                  </q-item-section>
                </q-item>
              </q-list>
            </div>
            <q-inner-loading :showing="isLoading"></q-inner-loading>
            <div v-if="!isLoading && !products.length" class="text-center q-pt-lg">Tidak tidak ditemukan</div>
          </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'ProductPromoted',
  data() {
    return {
      product_ids: [],
      promoted: null,
      isLoading: false,
      syncLoading: false,
      searchModal: false,
      search: '',
      productSearch: [],
      products: []
    }
  },
  methods: {
    getPromoDetail() {
      Api().get('getPromoDetail/'+ this.$route.params.id).then(response => {
       if(response.status == 200) {
          this.products = response.data.results.products
          this.promoted = response.data.results.promoted
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
      Api().get('findNotDiscountProduct').then(response => {
        if(response.status == 200) {
          this.productSearch = response.data.results
        }
      })
      .finally(() => {
        this.isLoading = false
      })
    },
    selectProduct(id) {
      if(this.product_ids.includes(id)) {
        this.product_ids = this.product_ids.filter(pr => pr != id)
      } else {
        this.product_ids.push(id)
      }
    },
    isSelected(id) {
      if(this.products.filter(pi => pi.id == id).length > 0) {
        return true
      }
      return false
    },
    handleAddProductPromoted() {
      this.search = ''
      this.searchModal = true
      this.productSearch = []
      this.findProduct()
    },
    syncProduct(id) {
      this.syncLoading = true
      Api().post('toggleProductPromo', {
          promote_id: this.$route.params.id, 
          product_id: id 
        }).then(() => {
        this.getProductPromo()
      })
      .finally(() => {
        this.syncLoading = false
      })
    },
  },
  mounted() {
    this.getPromoDetail()
  }
}
</script>

<style>

</style>