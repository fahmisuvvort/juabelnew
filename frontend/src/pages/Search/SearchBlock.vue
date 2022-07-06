<template>
  <q-page padding>
    <q-header class="text-primary bg-white">
       <q-toolbar>
         <q-btn v-go-back.single
            flat round dense
            icon="arrow_back" />
            <q-toolbar-title class="text-weight-bold brand">Cari</q-toolbar-title>
          <shopping-cart  />
       </q-toolbar>
    </q-header>
    <div class="col bg-white border">
      <q-input ref="input" outlined dense color="grey-2" v-model="search" autofocus @keyup.enter="searchNow" placeholder="ketik nama produk.."
      >
      <template v-slot:append>
        <q-icon
          name="search"
          class="cursor-pointer"
          @click="searchNow"
        />
      </template>
      </q-input>
    </div>
    <div>
      <q-list separator>
        <template v-if="search">
      <q-item>
        <q-item-section top>
          <div style="cursor:pointer" @click="searchNow">{{ search }}</div> 
        </q-item-section>
        <q-item-section top side>
          <div class="text-grey-8 q-gutter-xs">
            <q-btn size="12px" flat dense round icon="cancel" @click="search = ''"/>
          </div>
        </q-item-section>
      </q-item>
        </template>
      <template v-if="lastSearch.length">
      <div class="text-grey-7 text-weight-medium q-py-md">
          Pencarian sebelumnya
      </div>
      </template>
    </q-list>
    <div>
      <q-chip v-for="(item, index) in lastSearch" :key="index" removable @remove="removeLastSearch(index)" 
      :color="isReady(item) ? 'teal' : ''" class="cursor-pointer" @click.native="getSearch(item)" 
      :text-color="isReady(item) ? 'white' : ''">
        {{ item }}
      </q-chip>
    </div>
    </div>
  </q-page>
</template>
<script>
import ShoppingCart from 'components/ShoppingCart.vue'
export default {
  components: { ShoppingCart },
  data () {
    return {
      search: '',
      lastSearch: []
    }
  },
  methods: {
    searchNow() {
      if(this.search || this.search != '') {
        this.$router.push({name: 'ProductSearch', query: {q: this.search }})
        if (this.lastSearch.indexOf(this.search) > -1) {
          return
        } else {
          this.lastSearch.push(this.search)
        }
        this.setLocalItem()
        this.search = ''
      }
    },
    isReady(item) {
      return this.search.toLowerCase() == item.toLowerCase()
    },
    getSearch(str) {
      this.search = str
      this.searchNow()
    },
    searchFrom(str) {
      this.search = str
      this.searchNow()
    },
    removeLastSearch(index) {
      this.lastSearch = this.lastSearch.filter((el,i) => i != index)
       this.setLocalItem()
    },
    setLocalItem() {
      localStorage.setItem('lastSearch', JSON.stringify(this.lastSearch))
    }

  },
  mounted() {
    if(localStorage.getItem('lastSearch')){
      this.lastSearch = JSON.parse(localStorage.getItem('lastSearch'))
    }
    setTimeout(() => {
      this.$refs.input.focus()
    },300)
  }
}
</script>

<style>

</style>