<template>
  <q-page style="background:white;" class="q-pb-xl relative" :class="{'flex flex-center' : !ready }">
    <template v-if="ready && product">
    <div class="q-pa-md header-top">
      <div class="flex justify-between">
        <q-btn 
        @click="backButton"
        flat dense icon="keyboard_backspace" icon-size="27px" style="cursor:pointer;opacity:.9;">
        </q-btn>
        <shopping-cart />
      </div>
    </div>
    <div class="col relative overflow-x-hidden">
      <q-carousel
        ref="carousel"
        :style="cStyle"
        class="img-carousel"
        animated
        swipeable
        v-model="slide"
        :fullscreen.sync="fullscreen"
        navigation
        infinite
        :height="height"
        style="max-height:574px;"
      >
        <q-carousel-slide v-for="(img, index) in product.assets" :key="index" :name="index+1" :img-src="img.src" ratio="1" />

        <template v-slot:control>
          <q-carousel-control
            position="bottom-right"
            :offset="[18, 40]"
          >
            <q-btn
              push round dense color="white" text-color="primary"
              :icon="fullscreen ? 'fullscreen_exit' : 'fullscreen'"
              @click="fullscreen = !fullscreen"
            />
          </q-carousel-control>
        </template>
      </q-carousel>
      <q-card class="product-detail relative box-shadow">
        <q-card-section class="q-pt-xs">
          
          <h1 class="text-h6 text-weight-semibold q-mb-md" v-if="product">{{ product.title }}</h1>
          <div class="row items-center justify-between">
            <div class="flex items-center">
              <div class="flex items-center text-secondary">
                  <span class="text-md">Rp</span>
                  <span class="text-lg text-weight-medium">
                  {{ $money(parseInt(totalPrice)) }} 
                  </span>
                </div>
                <div class="flex items-center text-strike text-xs q-ml-xs" v-if="product.pricing.is_discount">
                  <span class="text-sm">Rp</span>
                  <span class="text-md">{{ $money(product.pricing.default_price * quantity) }} </span>
                </div>
              <div>
              </div>
            </div>
            <div class="row q-gutter-md text-h6 items-center">
              <q-btn flat round icon="remove_circle_outline" size="24" @click="decrementQty" style="cursor:pointer;"></q-btn>
              <div>{{ quantity }}</div>
              <q-btn flat round icon="add_circle_outline" size="24" @click="incrementQty" style="cursor:pointer;"></q-btn>
            </div>
          </div>

          <div class="row items-center q-gutter-x-sm">
            <q-rating 
              v-model="productRating"
              readonly
              color="accent"
              icon="star_border"
              icon-selected="star"
              icon-half="star_half"
              size="1.3rem" 
            />
            <div class="text-weight-medium text-sm"> {{ product.reviews_count > 0 ? product.reviews_count +' ulasan' : ''}}</div>
          </div>
        </q-card-section>
      </q-card>

     <div class="box-shadow bg-white q-pa-md q-mt-md" v-if="product.varians.length">
        <div>
          <div class="text-md">Pilih Varian <span class="text-sm text-weight-normal text-grey-7"></span></div>
          <div class="q-mt-sm">
          <div class="q-mb-xs">{{ product.varians[0].label}}</div>
          <div class="q-gutter-sm">
            <q-btn class="product-varian--btn" outline v-for="item in product.varians" :key="item.id" :label="item.value" :color="varianSelected && varianSelected.id == item.id? 'accent' : 'grey-9'" @click="selectProductVarian(item)">
            <badge-tick v-if="varianSelected && varianSelected.id == item.id " />
            </q-btn>
          </div>
          </div>
            <div class="q-mt-md" v-if="varianSelected && varianSelected.has_subvarian">
              <div class="q-mb-xs">{{ varianSelected.subvarian[0].label }}</div>
              <div class="q-gutter-sm">
                <q-btn outline v-for="item in varianSelected.subvarian" 
                :key="item.id" 
                :label="item.value" 
                :disable="item.stock < 1 && !item.is_preorder"
                @click="selectProductSubvarian(item)" 
                :color="subvarianSelected && subvarianSelected.id == item.id ? 'accent' : 'grey-9'" 
                class="relative product-variation product-varian--btn"
                >
                  <badge-tick v-if="subvarianSelected && subvarianSelected.id == item.id " />
                </q-btn>
              </div>
          </div>
        </div>
      </div>
     
      <q-card class="box-shadow q-mt-md">
        <q-tabs 
        v-model="tab"
        active-color="accent"
        align="left"
        >
          <q-tab name="Description" label="Deskripsi Produk"></q-tab>
          <q-tab name="Review" label="Ulasan Produk"></q-tab>
        </q-tabs>
         <q-separator />
        <q-tab-panels v-model="tab">
          <q-tab-panel name="Description">
            <div id="description" class="q-mt-md" style="min-height:150px;">
              <div>
                  <h3 class="text-md q-mb-sm">Deskripsi Produk</h3>
                <div class="" v-html="product.description"></div>
              </div>
              <div id="product-images" class="q-mt-lg">
                <div class="product-image--container">
                  <div v-for="img in product.images" :key="img.id" class="product-image">
                    <div class="product-image--content">
                      <img class="product-image--img" :src="img.src" :alt="img.caption" />
                      <div class="product-image--caption">{{ img.caption }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </q-tab-panel>
          <q-tab-panel name="Review">
            <div id="ulasan" class="q-mt-lg">
              <div class="flex column justify-center items-center">
                <div class="text-center" v-if="productRating > 0">
                  <div class="text-3xl text-weight-bold">{{ product.rating }}</div>
                  <q-rating 
                    readonly
                    v-model="productRating"
                    color="accent"
                    icon="star_border"
                    icon-selected="star"
                    icon-half="star_half"
                    size="30px"
                  />
                </div>
                <div class="text-weight-medium text-md q-my-sm">
                {{ product.reviews_count > 0 ? 'Total ' + product.reviews_count +' ulasan' : 'Belum ada ulasan'}}
                </div>
                <q-btn outline color="accent" @click="handleReviewModal" label="Berikan ulasan" class="q-my-xs"></q-btn>
              </div>
              <div class="q-pt-md">
                <div class="q-gutter-y-md">
                  <div v-for="(review, index) in productReviews" :key="index">
                    <q-list>
                      <q-item class="q-px-xs">
                        <q-item-section>
                          <q-item-label class="text-md">{{ review.name }}</q-item-label>
                           <q-rating 
                            readonly
                            v-model="review.rating"
                            color="accent"
                            icon="star_border"
                            icon-selected="star"
                            icon-half="star_half"
                            size="1.1rem"
                          />
                        </q-item-section>
                        <q-item-section side>
                          <div class="text-xs">{{ review.created_format }}</div>
                        </q-item-section>
                      </q-item>
                    </q-list>
                    <div class="q-pa-sm bg-grey-1 text-grey-7 text-sm"> {{ review.comment }} </div>
                  </div>
                </div>
              </div>
            <div class="q-my-md row justify-center">
              <q-btn flat color="primary" :loading="loadMoreLoading" v-if="productReviews.length < product.reviews_count" label="loadmore.." @click="loadReview">
                <template v-slot:loading>
                  <q-spinner-facebook />
                </template>
              </q-btn>
            </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
    <q-footer class="q-gutter-x-sm flex q-pa-md bg-white">
        <!-- <q-btn @click="btnFavorite" icon="favorite" outline round :color="isLike? 'pink' : 'dark'"></q-btn> -->
        <q-btn unelevated rounded outline @click="chat" icon="chat" label="Chat" color="primary" class="col"></q-btn>
        <q-btn unelevated rounded @click="addNewItem" icon="shopping_basket" label="Beli" color="primary" class="col"></q-btn>
    </q-footer>
    </template>
      <q-inner-loading :showing="!ready">
        <q-spinner-facebook size="50px" color="primary"/>
      </q-inner-loading>
      <q-dialog v-model="reviewModal">
        <div class="q-card" style="width:100%;max-width:360px;">
          <q-card-section>
          <q-form @submit.prevent="submitReview">
            <div>
            <div class="text-subtitle2 q-mb-sm">Berikan Ulasan Anda</div>
              <q-rating 
                v-model="form.rating"
                color="amber"
                icon="star_border"
                icon-selected="star"
                icon-half="star_half"
                size="sm" 
              />
            <div class="q-my-md q-gutter-y-xs">
              <q-input 
              dense 
              label="Nama Anda" 
              v-model="form.name"
              :rules="[val => val && val != '' || 'Wajib disisi']"
              ></q-input>
              <q-input 
              dense 
              label="Ulasan Anda" 
              type="textarea" 
              v-model="form.comment" 
              rows="3"
              :rules="[val => val && val != '' || 'Wajib disisi']"
              ></q-input>
            </div>
            <div class="q-gutter-y-sm q-my-md items-center text-grey">
              <div class="text-grey text-xs">Jawab tantangan berikut, hanya untuk memastikan anda bukan robot</div>
              <div class="row q-gutter-x-sm items-center">
                <div class="text-weight-bold bg-dark text-white q-px-sm q-py-xs rounded">{{ number2 }} + {{ number1 }} </div> 
                <div class="text-weight-bold"> = </div> 
               <input class="rounded text-grey-9" type="text" v-model="jawaban" style="width:60px;padding:3px 6px;border:1px solid grey">
              </div>
            </div>
            <div class="row justify-end q-gutter-x-sm">
              <q-btn unelevated type="button" @click.prevent="reviewModal = false" label="Batal" color="secondary"></q-btn>
              <q-btn unelevated :disabled="chalengeTesting" type="submit" :loading="loading" label="Kirim Ulasan" color="primary"></q-btn>
            </div>
          </div>
          </q-form>
          </q-card-section>
        </div>
      </q-dialog>
      <q-dialog v-model="chatModal">
      <q-card style="max-width:450px;width:100%;" class="text-grey-9">
        <div class="text-weight-bold q-py-sm q-px-md bg-primary text-white">Kirim pesan / tanya ke penjual</div>
        <q-card-section class="transition-height">
          <div class="q-mb-sm text-subtitle2" v-if="this.product"># {{ product.title }}</div>
          <q-input outlined autogrow autofocus v-model="chatText" placeholder="contoh: Halo Admin, Apakah ini masih ada?"></q-input>
          <div class="q-pt-sm">
            <div class="q-pa-xs text-xs cursor-pointer" v-for="chat in defaultChat" :key="chat" @click="changeChatText(chat)">
              <span>{{ chat }}</span>
            </div>
          </div>
        </q-card-section>
        <q-card-actions class="justify-end">
          <q-btn @click="closeChatModal" flat label="Batal"></q-btn>
          <q-btn @click="submitChat" :disabled="!chatText" flat color="primary" label="Kirim"></q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog 
    v-model="cartModal"
    position="bottom"
    transition-show="slide-up"
    transition-hide="slide-down"
    >
      <q-card flat class="max-width bg-white" v-if="product">
        <q-linear-progress size="10px" :value="100" />
          <q-card-section>
            <!-- <q-item-label class="text-weight-medium">{{ product.title }}</q-item-label> -->
          <q-list>
            <q-item>
              <q-item-section avatar>
                <q-img :src="product.assets[0].src" width="60px" class="rounded-borders"></q-img>
              </q-item-section>
              <q-item-section top>
                <div class="text-md text-weight-meduim q-mb-sm">Produk berhasil ditambahkan.</div>
                <q-item-label caption>Anda bisa lanjut kehalaman checkout atau berbelanja kembali</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
          <div class="flex justify-end q-gutter-x-sm q-pt-sm">
            <q-btn flat no-caps @click="cartModal = false" label="Berbelanja Lagi" color="primary"></q-btn>
            <q-btn unelevated no-caps :to="{ name: 'Cart' }" label="Lanjut Checkout" color="primary"></q-btn>
          </div>
        </q-card-section>
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
    <q-dialog 
      v-model="formVariantModal"
      position="bottom"
      transition-show="slide-up"
      transition-hide="slide-down"
      >
      <q-card class="max-width" flat v-if="product && product.varians.length">
        <q-card-section>
          <div class="text-weight-medium text-lg q-mb-sm text-secondary">{{ moneyIDR(parseInt(totalPrice)) }}</div>
            <div>
              <div class="text-md">Pilih Varian <span class="text-sm text-weight-normal text-grey-7"></span></div>
              <div class="q-mt-sm">
              <div class="q-mb-xs">{{ product.varians[0].label}}</div>
              <div class="q-gutter-sm">
                <q-btn class="product-varian--btn" outline v-for="item in product.varians" :key="item.id" :label="item.value" :color="varianSelected && varianSelected.id == item.id? 'accent' : 'grey-9'" @click="selectProductVarian(item)">
                <badge-tick v-if="varianSelected && varianSelected.id == item.id " />
                </q-btn>
              </div>
              </div>
                <div class="q-mt-md" v-if="varianSelected && varianSelected.has_subvarian">
                  <div class="q-mb-xs">{{ varianSelected.subvarian[0].label }}</div>
                  <div class="q-gutter-sm">
                    <q-btn outline v-for="item in varianSelected.subvarian" 
                    :key="item.id" 
                    :label="item.value" 
                    :disable="item.stock < 1 && !item.is_preorder"
                    @click="subvarianSelected = item" 
                    :color="subvarianSelected && subvarianSelected.id == item.id ? 'accent' : 'grey-9'" 
                    class="relative product-variation product-varian--btn"
                    >
                      <badge-tick v-if="subvarianSelected && subvarianSelected.id == item.id " />
                    </q-btn>
                  </div>
              </div>
            </div>
        </q-card-section>
        <q-card-section>
        <q-btn unelevated rounded @click="addNewItem" icon="shopping_basket" label="Beli Sekarang" color="green" class="full-width"></q-btn>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { mapMutations, mapActions } from 'vuex'
import ShoppingCart from 'components/ShoppingCart.vue'
import BadgeTick from 'components/BadgeTick.vue'
export default {
  name: 'ProductShow',
  components: { ShoppingCart, BadgeTick },
  data () {
    return {
      tab: 'Description',
      defaultChat: ['Apakah ini masih ada?', 'Apakah bisa grosir?'],
      chatText: '',
      chatModal: false,
      reviewModal: false,
      number1: 0,
      number2: 0,
      jawaban: '',
      loading: false,
      slide: 1,
      quantity: 1,
      discount: 0,
      fullscreen: false,
      shop: this.$store.state.shop,
      ready: false, 
      loadMoreLoading: false,
      form: {
        product_id: null,
        name: '',
        comment: '',
        rating: 0
      },
      cartModal: false,
      alreadyItemModal: false,
      varianSelected: null,
      subvarianSelected: null,
      formVariantModal: false,
      product: null,
      productReviews: []
    }
  },
  computed: {
    session_id() {
      return this.$store.state.session_id
    },
    chalengeTesting() {
      return this.number1+this.number2 != this.jawaban
    },
    productRating() {
      return parseFloat(this.product.rating)
    },
    carts() {
      // return this.$store.getters['cart/getCarts']
      return this.$store.state.cart.carts
    },
    favorites: function() {
      return this.$store.state.product.favorites
    },
    isLike() {
      if(this.favorites.length > 0) {
         return this.favorites.find(el => el == this.$route.params.id) ? true : false
      }
      return false;
    },
    cStyle() {
      if(!this.fullscreen && this.$q.screen.width < 560 && this.$q.screen.width > 200) {
        return 'height:'+ this.$q.screen.width +'px'
      }
        return ''
    },
    height() {
      return this.$q.screen.width+'px'
    },
    productStock() {
      if(this.product.varians.length) {
        if(this.subvarianSelected) {
          return this.subvarianSelected.stock
        }
        return 0
      } 
      return this.product.stock
    },
    currentStock() {
      let hasCart = this.carts.find(el => el.sku == this.currentProductSku)

      if(this.isHasVarian) {

        if(this.varianSelected) {  
        
          if(!this.varianSelected.has_subvarian) {
            return hasCart != undefined ? this.varianSelected.stock-hasCart.quantity : this.varianSelected.stock
          }

          if(this.subvarianSelected) {
  
            return hasCart != undefined ? this.subvarianSelected.stock-hasCart.quantity : this.subvarianSelected.stock
          }

          return hasCart != undefined ? this.productStock-hasCart.quantity : this.productStock

        } else {

           if(this.subvarianSelected) {

            return hasCart != undefined ? this.subvarianSelected.stock-hasCart.quantity :  this.productStock
          }

          return hasCart != undefined ?  this.productStock-hasCart.quantity :  this.productStock
        }

      }else {

        return hasCart != undefined ?  this.productStock-hasCart.quantity :  this.productStock

      }

    },
    isHasVarian() {
      return this.product.varians.length > 0
    },
    currentProductSku() {
      if(this.varianSelected) {
        if(!this.varianSelected.has_subvarian) {
          return this.varianSelected.sku
        }
        if(this.subvarianSelected) {
          return this.subvarianSelected.sku
        }
      }
      return this.product.sku ? this.product.sku : this.product.id
    },
    realPrice() {
      if(this.varianSelected) {

        if(!this.varianSelected.has_subvarian) {
          return this.product.pricing.current_price + this.varianSelected.price
        }

        if(this.subvarianSelected) {
  
          return  this.product.pricing.current_price + this.subvarianSelected.price
        }
      }
      return this.product.pricing.current_price
    },
    totalPrice() {
      if(this.varianSelected) {

        if(!this.varianSelected.has_subvarian) {

          return (this.product.pricing.current_price + this.varianSelected.price) * this.quantity

        } else {

          if(this.subvarianSelected) {
    
            return (this.product.pricing.current_price + this.subvarianSelected.price) * this.quantity
          } 
  
            return (this.product.pricing.current_price + this.varianSelected.subvarian[0].price) * this.quantity
        }

      }

      return this.product.pricing.current_price * this.quantity
    },
    cartTextButton() {
       if(this.currentStock >= 1) {
            return 'Beli'
          }
        return 'Habis'
    },
    cartTextColor() {
      if(this.currentStock >=1){
        return 'primary'
      }
      return 'grey-7'
    },

  },
  methods: {
    ...mapMutations('product',['ADD_REMOVE_TO_FAVORITE']),
    ...mapActions('product', ['getProductBySlug', 'loadProductReview', 'addProductReview']),
    selectProductVarian(item) {
      this.varianSelected = item
      this.subvarianSelected = null
      this.quantity = 1
    },
    selectProductSubvarian(item) {
      this.subvarianSelected = item
      this.quantity = 1
    },
    backButton() {
      if(window.history.length > 2) {
        window.history.back()
      }else {
        this.$router.push({name: 'ProductIndex'})
      }
    },
    getIcon(rating) {
      let rtg = parseInt(rating)

      if(rtg <= 2) {
        return 'sentiment_very_dissatisfied'
      } else if(rtg == 3) {
        return 'sentiment_neutral'
      } else if(rtg == 4) {
        return 'sentiment_satisfied_alt'
      }else {
         return 'sentiment_very_satisfied'
      }
    },
    varItemGetColor(id) {
      if(this.varianSelected) {
        return this.varianSelected.id == id ?  false : true
      }
      return true
    },
    varValueGetColor(id) {
      if(this.subvarianSelected) {
        return this.subvarianSelected.id == id ? false : true
      }
      return true
    },
    handleVariantItemSelectted(item) {
      this.varianSelected = item
      this.subvarianSelected = null
      this.quantity = 1
      this.setViewProductPattern()
    },
    handleSelectedItemValue(value) {
      this.subvarianSelected = value
      this.quantity = 1
      this.setViewProductPattern()
    },
    discountPriceFormat() {
      return (this.subtotal()*this.discount)/100
    },
    subQty() {
      if(this.carts.length > 1) {
        return this.carts.reduce((a,b) => a.quantity + b.quantity) 
      }
      return this.carts[0].quantity
    },
    subtotal() {
      if(this.carts.length > 1) {
        let j = [];
        this.carts.forEach(element => {
          j.push(element.quantity*element.price)
        });
        return j.reduce((a,b) => a + b)
      }
      return this.carts[0].quantity * this.carts[0].price
    },
    total () {
      if(this.discount || this.discount !== 0) {
        return (this.subtotal() - this.discountPriceFormat())
      }
      return this.subtotal()
    },
    stockStyle() {
      if(this.currentStock > 0 && this.currentStock <= 5) return 'text-amber'
      if(this.currentStock <= 0 ) return 'text-red'
      return 'text-green'
    },
    addToCart() {
      this.formVariantModal = false

      let cartItem = {
        session_id: this.session_id, 
        product_id: this.product.id, 
        product_stock: this.currentStock, 
        sku: this.currentProductSku, 
        name: this.product.title, 
        price: this.realPrice, 
        quantity: this.quantity, 
        note: this.getVarianTextNote(),
        product_url: this.getRoutePath(), 
        image_url: this.product.assets[0].src, 
        weight: this.product.weight*this.quantity,
        seller: this.product.seller ? this.product.seller : null
      }

     if(! this.session_id) this.makeSessionId()

      this.$store.dispatch('cart/addToCart' , cartItem)

        this.quantity = 1
    },
    showNotifyHasSelectVarian() {
      if(this.formVariantModal) {
        this.$q.notify({
          type: 'info',
          message: 'Silahkan pilih produk varian terlebih dahulu',
        })
      } else {
        this.formVariantModal = true
      }
    },
    addNewItem() {
      if(!this.checkSeller()){
        this.$q.dialog({
          title: 'Maaf produk berbeda penjual dengan dengan di keranjang',
          message: 'Silahkan hapus produk yang di keranjang jika ingin tetap akan membeli produk ini.'
        })
        return false
      }

      if(this.isHasVarian) {
        if(this.varianSelected) {

          if(this.varianSelected.has_subvarian) {

            if(!this.subvarianSelected) {

              this.showNotifyHasSelectVarian()

              return 
            }
          }

        } else {
          this.showNotifyHasSelectVarian()
          return

        }
      }
     
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
    checkSeller() {
      if(this.carts.length) {
        if(this.product.seller) {
          if(!this.carts[0].seler || this.carts[0].seller.id != this.product.seller.id) {
            return false
          }
        }else if(this.carts[0].seller) {
          return false
        }
      }
      return true

    },
    checkCart() {
      return new Promise((resolve, reject) => {
        let cartAlready;

          cartAlready = this.carts.find(el => el.sku == this.currentProductSku)

          if(cartAlready != undefined) {

            reject()

          } else {

            resolve()
          }

      })
    },
    getVarianTextNote() {
       let str = ''
      if(this.varianSelected) {
        if(this.varianSelected.has_subvarian && this.subvarianSelected) {
          str += `${this.varianSelected.label} ${this.varianSelected.value}, ${this.subvarianSelected.label} ${this.subvarianSelected.value}`
        } else {
          str += `${this.varianSelected.label} ${this.varianSelected.value}`
        }
      }
      return str
    },
    getRoutePath() {
      let props = this.$router.resolve({ 
        name: 'ProductShow',
        params: { slug: this.product.slug },
      });

      return location.origin + props.href;
    },
    btnFavorite() {
      this.ADD_REMOVE_TO_FAVORITE(this.product.id)
    },
    setPrice() {
      this.priceTotal = this.price*this.quantity
    },
    checkVarianIsReady() {
      if(this.isHasVarian) {
        if(!this.varianSelected) return false

        if(this.varianSelected.has_subvarian) {
          if(!this.subvarianSelected) return false
        }
      }
      return true
    },
    incrementQty() {
      if(!this.checkVarianIsReady()) {
        this.$q.dialog({
          title: 'Pilih Varian!',
          message: 'Silahkan pilih varian untuk melanjutkan.'
        })
        return
      } 

      if(this.quantity < this.currentStock) {
        this.quantity += 1
      } else {
        this.$q.dialog({
          title: 'Warning!',
          message: 'Stok tidak cukup, stok tersisa ' + this.currentStock + ' item.'
        })
      }

    },
    decrementQty() {
       if(!this.checkVarianIsReady) {
        this.$q.dialog({
          title: 'Pilih Varian!',
          message: 'Silahkan pilih varian untuk melanjutkan.'
        })
        return
      } 

      if(this.quantity > 1) {
        this.quantity -= 1
      }
    },
    getTeaser(html) {
      if(html) {
        let strippedString = html.replace(/(<([^>]+)>)/gi, "");
        return strippedString.substr(0, 80)
      } else {
        return ''
      }
    },
    handleReviewModal() {
      this.getRandomNumber()
      this.reviewModal = true
    },
    submitReview() {
      if(this.number1+this.number2 != this.jawaban) {
        this.$q.notify({
          type: 'negative',
          message: 'Jawaban Salah, silahkan jawab dengan benar.'
        })
        this.getRandomNumber()
        return
      }
      this.jawaban = ''
      this.getRandomNumber()
      this.form.product_id = this.product.id
      if(this.form.name && this.form.comment && this.form.rating) {
        this.loading = true
        this.reviewModal = false
        this.addProductReview(this.form).then(() => {
          this.getProduct()
        })
        this.resetForm()
        this.loading = false
      } else {
        this.$q.notify({
          type: 'warning',
          message: 'Semua field harus di isi'
        })
      }
    },
    resetForm() {
      this.form.name = ''
      this.form.comment = ''
    },
    getReview() {
      this.loadMoreLoading = true
      this.loadProductReview({ product_id: this.product.id }).then(response => {
        if(response.status == 200) {
          this.loadMoreLoading = false
          this.productReviews = response.data.results
        }
      }).catch(err => {
         this.loadMoreLoading = false
      })
    },
    loadReview() {
      this.loadMoreLoading = true
      this.loadProductReview({ product_id: this.product.id, skip: this.productReviews.length }).then(response => {
        if(response.status == 200) {
          this.loadMoreLoading = false
          this.productReviews = [... this.productReviews, ...response.data.results]
        }
      }).catch(err => {
         this.loadMoreLoading = false
      })
    },
    getProduct() {
      this.getProductBySlug(this.$route.params.slug).then(response => {
        if(response.status == 200) {
          this.product = response.data.data
          this.ready = true
          if(this.isHasVarian) {
            if(this.product.varians[0].has_subvarian) {
              this.varianSelected = this.product.varians[0];
            }
          }
          this.getReview()
        } else {
          // this.$router.push({name: 'ProductIndex'})
        }
      }).catch(() => {
        // this.$router.push({name: 'ProductIndex'})
      })
    },
    getRandomNumber() {
      let number = [1,2,3,4,5,6,7,8,9]
      this.number1 = Math.floor((Math.random() * number.length));
      this.number2= Math.floor((Math.random() * number.length));
    },
    formatPhoneNumber(number) {
      let formatted = number.replace(/\D/g,'')

      if(formatted.startsWith('0')) {
        formatted = '62' + formatted.substr(1)
      }

      return formatted;
    },
    chat() {
      this.chatModal = true
    },
    closeChatModal() {
      this.chatText = ''
      this.chatModal = false
    },
    changeChatText(chat) {
      this.chatText = chat
    },
    submitChat() {
      let shopPhone = this.shop.phone
      if(!shopPhone) {
        this.$q.dialog({
            title: 'Maaf, Sedang masalah!',
            message: 'Silahkan coba kembali beberapa saat lagi, jika masih berlanjut silahkan hubungi kami.',
          })
          return
      }

      let link = 'https://api.whatsapp.com/send?phone=' + this.formatPhoneNumber(shopPhone) + '&text=' + encodeURI(this.chatText + '\nProduk: '+ this.product.title +'\n') + location.href;
      window.open(link, '_blank');

      setTimeout(() => {
        this.closeChatModal()
      }, 2000)

    },

  },
  created() {
    if(!this.product || this.product.slug != this.$route.params.slug) {
      this.getProduct()  

    } else {
      this.ready = true
    }
    this.getRandomNumber()
      
  },
  meta() {
    return {
      title: this.product?.title,
      meta: {
        description: { name: 'description', content: this.getTeaser(this.product?.description) },
        ogDescription: { property: 'og:description', content: this.getTeaser(this.product?.description)  },
        ogTitle:  { property: 'og:title', content: this.product?.title },
        ogImage:  { property: 'og:image', content: this.product?.assets[0].src },
      }
    }
  }
}
</script>

<style lang="scss">
.header-top {
  z-index:50;
  position:fixed;
  top:0;
  left:0;
  right:0;
  width:100%;
  background: transparent;
  background: linear-gradient(0deg, rgba(2, 0, 36, 0) 0%, rgba(0, 0, 5, 0.678) 100%); 
  color:#fff;
}
.q-body--fullscreen-mixin .img-carousel::after {
  height: 0;
}
.product-detail::before {
position: absolute;
  width: 100%;
  background-color:white;
  content: "";
  height: 20px;
  border-radius: 80px 80px 0 0;
  top: -20px;
  left: 0;
  .q-carousel__control.absolute.absolute-bottom-right {
    transform: translateY(-20px)
  }
  .q-carousel__navigation--bottom{
    transform: translateY(-20px)
  }
}
</style>