<template>
  <div class="full-height">
    <div class="column full-height relative bg-white box-shadow">
      <q-img v-if="product.assets.length" :src="product.assets[0].src" ratio="1" @click="show(product.slug)" class="cursor-pointer">
        <template v-slot:error>
          <div class="absolute-full flex flex-center bg-grey-6 text-white">
            Cannot load image
          </div>
        </template>
      </q-img>
      <q-img v-else src="/static/no_image.png" ratio="1" @click="show(product.slug)" class="cursor-pointer">
      </q-img>
      <div class="relative col column q-gutter-y-xs justify-between q-pb-md q-px-sm q-pt-sm overflow-hidden full-width">
        <div>
          <div class="flex justify-between items-center">
            <q-rating 
              readonly
              v-model="rating"
              color="accent"
              icon="star_border"
              icon-selected="star"
              icon-half="star_half"
              :size="ratingSize"
            />
              <favorite-button :product_id="product.id" />
          </div>
          <div class="full-width q-mt-xs">
            <div class="text-subtitle ellipsis-2-lines">{{ product.title }}</div>
          </div>
        </div>
       <div class="card-price-container">
          <div class="card-price text-secondary">
            <span class="prefix">Rp</span>
            <span class="amount">{{ $money(product.pricing.current_price) }}</span>
          </div>
          <div v-if="product.pricing.is_discount" class="card-discount text-strike text-grey-8">
            <span class="prefix">Rp</span>
            <span class="amount">{{ $money(product.pricing.default_price) }}</span>
            </div>
        </div>
      </div>
         <div v-if="product.pricing.is_discount" class="absolute top-0 z-50 bg-red-6 text-white" style="padding:2px;font-size:13px;">{{ product.pricing.discount_percent }}%</div>
    </div>
  </div>
</template>
<script>
import FavoriteButton from 'components/FavoriteButton.vue'
export default {
  name: 'ProductCard',
  props: { product: Object},
  components: { FavoriteButton },
  data() {
    return {
      rating: this.product.rating? parseFloat(this.product.rating) : 0.0
    }
  },
  computed: {
    ratingSize() {
      if(window.innerWidth < 481) {
        return '.9rem'
      }
      return '1.1rem'
    }
  },
  methods: {
    money(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number)
    },
    show(slug) {
      this.$router.push({name: 'ProductShow', params: { slug: slug }})
    },
  }
}
</script>