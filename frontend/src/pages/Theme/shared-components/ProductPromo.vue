<template>
  <div>
    <template v-for="promo in product_promo">
      <div :key="promo.id" v-if="promo" class="block-container bg-linear">
        <div class="block-content">
          <div class="auto-padding-side">
            <glide-product-promo :promo="promo" />
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import GlideProductPromo from 'components/GlideProductPromo.vue'
export default {
  props: ['product_promo'],
  components: { GlideProductPromo },
}
</script>
