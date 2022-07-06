<template>
<div class="favorite-button">
  <q-btn :outline="outline" class="btn-favorite" :padding="lg? '6px' : '4px'" @click="btnFavorite" icon="favorite" :size="lg? '15px' : '11px'" unelevated round :text-color="getFavoriteStyle()"></q-btn>
</div>
</template>

<script>
export default {
  props: {
    product_id: [Number, String],
    lg: {
      type: Boolean,
      default: false
    },
    outline: {
      type: Boolean,
      default: false
    }
  },
  computed: {
      favorites: function() {
        return this.$store.state.product.favorites
      }
  },
  methods: {
    getFavoriteStyle() {
       let m = this.favorites.find(e => e == this.product_id)
       if(m) return 'pink'
       return 'grey'
    },
    btnFavorite() {
      this.$store.commit('product/ADD_REMOVE_TO_FAVORITE', this.product_id)
    },
  }
}
</script>

<style>
.favorite-button {
  background-color: rgba(226, 226, 226, 0.438);
  border-radius: 50%;
}
</style>