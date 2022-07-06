<template>
  <q-page>
     <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List Produk
        </q-toolbar-title>
        <q-btn class="gt-xs" no-caps flat icon="add_circle" :to="{name: 'ProductCreate'}" label="Tambah Produk"/>
      </q-toolbar>
    </q-header>
    <div class="border q-pa-md row item-center q-gutter-x-sm">
      <div class="col">
        <q-input :loading="loading" ref="input" outlined dense color="grey-2" v-model="search" @keyup.enter="searchProduct" placeholder="ketik nama produk"
        >
        </q-input>
      </div>
        <q-btn unelevated label="Cari" @click="searchProduct" color="primary"></q-btn>
        <q-btn outline label="Reset" @click="getAdminProducts" color="primary"></q-btn>
      </div>
    <template v-if="products.available">
     <div class="q-pt-sm q-pb-xl">
      <q-list separator>
       <q-item v-for="product in products.item.data" :key="product.id" class="q-py-md">

         <q-item-section avatar class="q-pr-sm" top>
           <q-img v-if="product.assets" :src="product.assets[0].src" class="bg-white img-product-admin" ratio="1"/>
        </q-item-section>

        <q-item-section>
          <div class="q-gutter-y-sm">
            <q-item-label lines="2" class="text-md">{{ product.title }}</q-item-label>
            <q-item-label caption class="ellipsis-2-lines" v-html="product.description"></q-item-label>
            <q-item-label caption class="">
              <q-chip v-if="product.is_preorder" class="bg-teal text-white" size="sm">Preorder</q-chip>
              <q-chip outline color="teal" size="sm">{{ moneyIDR(product.price)}}</q-chip>
              </q-item-label>
            </div>
          <div class="q-mt-sm text-xs">
           <q-chip v-if="product.subcategory" dense size="12px" outline color="green-6">
            <q-avatar icon="sell" color="primary" text-color="white"/>
           {{ product.subcategory.title }}
          </q-chip>
          </div>
        </q-item-section>

        <q-item-section side>
          <div class="text-grey-8 q-gutter-xs column">
            <q-btn unelevated @click="remove(product.id)" size="sm" round icon="delete" glossy color="red"/>
            <q-btn unelevated :to="{ name: 'ProductEdit', params: {id: product.id }}" size="sm" round glossy color="info" icon="edit" />
            <q-btn unelevated :to="{ name: 'ProductShow', params: {slug: product.slug }}" size="sm" round glossy color="teal" icon="visibility" />
          </div>
        </q-item-section>
      </q-item>
    </q-list>
    <div class="q-my-md q-gutter-sm text-center" v-if="products.item.total > products.item.per_page">
      <div>{{ products.item.current_page }} of {{ products.item.last_page }} page</div>
      <q-btn no-caps size="12px" unelevated color="primary" @click="paginate(products.item.prev_page_url)" :disable="!products.item.prev_page_url">
        <span>Sebelumnya</span>
      </q-btn>
    
      <q-btn no-caps size="12px" unelevated color="primary" @click="paginate(products.item.next_page_url)" :disable="!products.item.next_page_url">
        <span>Selanjutnya</span>
      </q-btn>
    </div>
    </div>
    </template>
    <template v-else >
      <div class="text-center q-pt-xl">Tdak ada data</div>
    </template>
    <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
      <q-btn fab icon="add" color="primary" :to="{name: 'ProductCreate'}" glossy/>
    </q-page-sticky>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { Api } from 'boot/axios'
export default {
  name: 'AdminProductList',
  data() {
    return {
      pageNumber: 1,
      search: '',
      productSearch: []
    }
  },
  computed: {
    ...mapState({
      products: state => state.product.admin_products,
      loading: state => state.loading
    }),
  },
  methods: {
    ...mapActions('product', ['getAdminProducts', 'productDelete', 'searchAdminProducts']),
    searchProduct() {
      this.$store.commit('SET_LOADING', true)
      this.$refs.input.blur()
      this.searchAdminProducts(this.search).then(response => {
        if(response.status == 200) {
          this.$store.commit('product/SET_ADMIN_PRODUCTS', response.data.results)
        }
      }).finally(() => {
        this.$store.commit('SET_LOADING', false)
      })
    },
    remove(id) {
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.productDelete(id)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
    getMargin(product) {
      if(!product.buy_price || !product.price) {
        return 0
      } else {
        return product.price-product.buy_price
      }
    },
    paginate(url) {
      Api().get(url).then(response => {
        if(response.status == 200) {
          this.$store.commit('product/SET_ADMIN_PRODUCTS', response.data.results)
        }
      })
    }
  },
  created() {
    if(!this.products.item.data.length) this.getAdminProducts()
  }
}
</script>
