<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Diskon / Kupon
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-tabs 
    v-model="tab"
    class="text-primary"
    >
      <q-tab name="promo" label="Promo"></q-tab>
      <q-tab name="kupon" label="Kupon"></q-tab>
      <q-tab name="diskon" label="Diskon"></q-tab>
    </q-tabs>
    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="diskon" class="q-px-xs">
        <Discount />
      </q-tab-panel>

      <q-tab-panel name="kupon" class="q-px-xs">
       <Coupon />
      </q-tab-panel>

      <q-tab-panel name="promo" class="q-px-xs">
        <Promote />
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>

<script>
import Discount from './Discount.vue'
import Coupon from './Coupon.vue'
import Promote from './Promote.vue'
export default {
  name: 'DiscountIndex',
  components: {
    Discount,
    Coupon,
    Promote
  },
  data() {
    return {
      tab: 'promo'
    }
  },
  mounted() {
    if(this.$route.query.q) {
      this.tab = this.$route.query.q
    }
  }
}
</script>
