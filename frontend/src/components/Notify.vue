<template>
  <div class="notif-content" :class="{'show' : isShowen }" v-if="orderItem">
    <q-list>
      <q-item>
        <q-item-section avatar>
           <q-avatar>
            <img :src="orderItem.image" />
          </q-avatar>
        </q-item-section>
        <q-item-section>
          <q-item-label caption>{{ orderItem.customer_name }} telah membeli</q-item-label>
          <q-item-label class="ellipsis-2-lines">{{ orderItem.name }} </q-item-label>
            <div class="text-green-7 q-pa-xs text-xs">{{ orderItem.created }}</div>
        </q-item-section>
        <q-item-section side top>
          <q-btn icon="close" size="10px" color="primary" flat round @click="closeNotify"></q-btn>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data() {
    return {
      orders: [],
      isShowen: false,
      orderItem: null,
      currentOrderIndex: 0
    }
  },
  watch: {
    isShowen: function(val) {
      if(val) {
         setTimeout(() => {
          this.isShowen = false
        }, this.notifyTimeOut)
      }
    }
  },
  computed: {
    config() {
      return this.$store.state.config
    },
    orderItems() {
      return this.$store.state.order.orderItems
    },
    notifyInterval() {
      if(this.config){
        return this.config.notifypro_interval*1000
      } else {
        return 15*1000
      }
    },
    notifyTimeOut() {
      if(this.config){
        return this.config.notifypro_timeout*1000
      } else {
        return 4*1000
      }
    },
  },
  methods: {
    showNotif() {
       this.isShowen = true

      setInterval(() => {

        if(!this.orderItem) return

        if(this.currentOrderIndex < (this.orderItems.length -1)) {
          
          this.currentOrderIndex++

        } else {

          this.currentOrderIndex = 0
        } 

        this.setCurrentOrder()

      }, this.notifyInterval)

    },
    setCurrentOrder() {

      if(localStorage.getItem('is_hide_notify')) {

        this.isShowen = false

      } else {

        this.orderItem = this.orderItems.find((e,i) => i == this.currentOrderIndex)
        this.isShowen = true

      }

    },
    initialNotify() {

     this.orderItem = this.orderItems.find((e,i) => i == this.currentOrderIndex)

      setTimeout(() => {

          this.showNotif()

        }, this.notifyInterval*2)
    },
    closeNotify() {

      localStorage.setItem('is_hide_notify', true)
      this.isShowen = false

      setTimeout(() => {

        localStorage.removeItem('is_hide_notify')
        this.isShowen = true

      }, this.notifyInterval*8)

    },
    getRandomOrder() {
       Api().get('getRandomOrder').then(res => {
        if(res.status == 200) {
          this.$store.commit('order/SET_NOTIFY_ORDER_ITEMS', res.data.results)
          setTimeout(() => {

            if(this.orderItems.length){

              this.initialNotify()
              
            }

          }, 500)
        }
      })
    }
  },
  mounted() {
     if(!this.orderItems.length) {
       this.getRandomOrder()
     }
  }
}
</script>

<style>

</style>