<template>
  <div class="column">
    <q-btn unelevated :disabled="currentStock < 1" :label="textButon" no-caps @click="addNewItem" :color="cartTextColor"></q-btn>
    <q-dialog v-model="cartModal">
      <q-card style="width:100%;max-width:350px;">
        <q-card-section>
          <div class="q-mb-sm text-weight-medium text-md">Item berhasil ditambahkan.</div>
          <div>Akan lanjut ke halaman chekout?</div>
        </q-card-section>
        <q-card-actions class="justify-end q-gutter-x-sm">
          <q-btn flat no-caps @click="cartModal = false" label="Belanja Lagi" color="primary"></q-btn>
          <q-btn unelevated no-caps :to="{ name: 'Cart' }" label="Checkout" color="primary"></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="alreadyItemModal">
      <q-card style="width:100%;max-width:350px;">
        <q-card-section>
          <div class="q-mb-sm text-weight-medium text-md">Produk yang sama ada di keranjang</div>
          <div>Tetap ingin menambahkan? jika "YA" item akan di perbarui kuantitasnya.</div>
        </q-card-section>
        <q-card-actions class="justify-end q-gutter-x-sm">
          <q-btn flat no-caps @click="alreadyItemModal = false" label="Batalkan" color="primary"></q-btn>
          <q-btn unelevated no-caps @click="updateNewItem" label="YA Tambahkan" color="primary"></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
export default {
  name: 'CartButton',
  props: {
    product: Object,
    rounded: {
      type: Boolean,
      default: false
    },
    icon: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      cartModal: false,
      alreadyItemModal: false,
    }
  },
  computed: {
    session_id() {
      return this.$store.state.session_id
    },
    textButon() {
      if(this.$q.screen.width > 428) {
        if(this.currentStock >= 1) {
            return 'Beli Sekarang'
          }
        return 'Stok Habis'
        } else {
           if(this.currentStock >= 1) {
            return 'Beli'
          }
          return 'Habis'
        }
    },
    carts: function() {
      return this.$store.state.cart.carts
    },
    productStock() {
      return this.product.variant_items_sum_item_stock? this.product.variant_items_sum_item_stock : this.product.stock
    },
    currentStock() {
      let hasCart = this.carts.find(el => el.product_id == this.product.id)
      if(hasCart != undefined) {
       return this.productStock-hasCart.quantity
      } else {
        return this.productStock
      }
    },
    cartTextColor() {
      if(this.currentStock >=1){
        return 'primary'
      }
      return 'grey-7'
    }
  },
  methods: {
    addToCart() {
      if(! this.session_id) this.makeSessionId()
      this.$store.dispatch('cart/addToCart' , {
        session_id: this.session_id,
        product_id: this.product.id,
        sku: this.product.id,
        name: this.product.title, 
        price: this.product.price, 
        product_stock: this.product.stock, 
        quantity: 1, 
        product_url: this.getRoutePath(), 
        image_url: this.product.assets[0].src,
        weight: this.product.weight }) 
    },
    getRoutePath() {
      let props = this.$router.resolve({
        name: 'ProductShow', 
        params: { slug: this.product.slug }
        });
      return location.origin + props.href;
    },
    addNewItem() {
      this.checkCart().then(res => {
        this.addToCart()
        this.cartModal = true
      }).catch(err => {
        this.alreadyItemModal = true
      })
    },
    updateNewItem() {
      this.alreadyItemModal = false
      this.addToCart()
      this.cartModal = true
    },
    checkCart() {
      return new Promise((resolve, reject) => {
        let av = this.carts.filter(el => el.product_id == this.product.id)
        if(av.length) {
          reject('ada produk lain')
        } else {
          resolve('yes')
        }
      })
    },
    makeSessionId() {
      var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

      var result = '';
        for ( var i = 0; i < 60; i++ ) {
            result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }

        this.$store.commit('SET_SESSION_ID', result);
    }
    
  },
}
</script>
