<template>
  <q-page>
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'SellerHome'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Gudang Pengiriman
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
       <div>
    <q-form @submit.prevent="updateData">
      <q-card flat>
        <q-card-section>
          <div class="flex items-center justify-between">
            <div class="text-md text-weight-bold">Pengaturan Gudang Pengiriman</div>
          </div>
            <div class="q-mt-md">
              <div class="text-q-pa-xs text-grey-9">Nama Toko</div>
              <q-input filled v-model="form.shop_name"></q-input>
              <div v-if="config && config.is_shippable" class="q-mt-md">
                <div class="text-q-pa-xs text-grey-9">Gudang Pengiriman</div>
                <div @click="changeWarehouse" class="cursor-pointer q-pa-md full-width border q-filled">{{ warehouseTitle() }}</div>
              </div>
            </div>
        </q-card-section>
        <q-card-actions class="q-pa-md justify-end">
          <q-btn type="submit" label="Simpan Pengaturan" color="primary"></q-btn>
        </q-card-actions>
      </q-card>
    </q-form>
    <q-dialog v-model="modal">
      <q-card style="width:100%;max-width:500px;">
        <q-card-section>
          <div class="flex items-center justify-between">
            <div class="text-md text-weight-bold q-mb-sm">Pilih Gudang Pengiriman</div>
            <q-btn flat icon="close" v-close-popup></q-btn>
          </div>
          <div class="q-pa-sm q-gutter-y-sm">
            <q-input ref="warehouse" :loading="searchLoading && searchType == 'warehouse'" placeholder="Ketik nama kecamatan" v-model="search" debounce="1000" @input="searchWarehouseData"></q-input>
            <transition
              appear
              enter-active-class="animated fadeIn"
              leave-active-class="animated fadeOut"
            >
            <div style="min-height:100px;max-height:300px;overflow-y:auto;" class="relative bg-grey-2" v-if="searchType == 'warehouse' && subdistrictOptions.length">
              <q-list>
                <q-item v-for="item in subdistrictOptions" :key="item.id" clickable @click="selectSubdistrict(item)">
                  <q-item-section>
                    <q-item-label>{{ item.subdistrict_name }} - {{ item.type }} {{ item.city }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
                <q-inner-loading
                  :showing="searchLoading"
                >
                </q-inner-loading>
              </div>
            </transition>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'Ekspedisi',
  data () {
    return {
      codListModal: false,
      modal: false,
      rajaongkirtypes: ['starter', 'basic', 'pro'],
      subdistrictOptions: [],
      search: '',
      isWarehouseSearch: false,
      searchType: 'cod',
      searchLoading: false,
      form: {
        shop_name: '',
        warehouse: null,
      },
    }
  },
  computed: {
    config: function() {
      return this.$store.state.config
    }
  },
  mounted() {
    this.getSeller()
  },
  methods: {
    changeWarehouse() {
      this.modal = true
      setTimeout(() => {
        this.$refs.warehouse.focus()
      }, 300)
    },
    warehouseTitle() {
      if(this.form.warehouse) {
        return `${this.form.warehouse.subdistrict_name} - ${this.form.warehouse.type} ${this.form.warehouse.city}, ${this.form.warehouse.province}`
      }
      return 'Pilih Gudang Pengiriman'
    },
    setConfig(item) { 
      this.form.warehouse = item.warehouse
      this.form.shop_name = item.shop_name
    },
    selectCourierType() {
      this.form.rajaongkir_couriers = ''
    },
    isCourierActive(name) {
      if(this.courierHasSelect.length) {

        if(this.courierHasSelect.includes(name)) {
          return true

        } else {
          return false
        }
      }
      return false
    },
    handleSelectCourier(evt) {
      let courierTemp = this.courierHasSelect

      if(courierTemp.includes(evt)) {
        courierTemp = courierTemp.filter(el => el != evt)
      } else {
        courierTemp.push(evt)
      }
      if(courierTemp.length) {
        this.form.rajaongkir_couriers = courierTemp.join(',')
      }
    },
    showNotify(error = '') {
      if(error) {
        this.$q.notify({
          type: 'negative',
          message: 'Gagal memperbarui data'
        })
      } else {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil memperbarui data'
        })
      }
      
    },
    updateData() {
      Api().post('updateSeller',  this.form).then(() => {
        this.$store.dispatch('getAdminConfig')
        this.showNotify()
      }).catch(() => {
        this.showNotify(error)    
      })
    },
    submitWarehouse() {
      this.updateData()
    },
    setLoading(status) {
      this.$store.commit('SET_LOADING', status)
    },
    selectSubdistrict(item) {
      this.form.warehouse_id = item.city_id
      this.form.warehouse = item
      this.modal = false
      this.subdistrictOptions = []
      this.search = ''
    },
    selectCodItemData(data) {
      let hasData = this.form.cod_list.filter(elj => elj.subdistrict_id == data.subdistrict_id)
      if(hasData.length) {
        this.form.cod_list = this.form.cod_list.filter(elm => elm.subdistrict_id != data.subdistrict_id)
      } else {
        }
      this.form.cod_list.push({...data, price: 0})
      this.search = ''
      this.subdistrictOptions = []
    },
    isInWarehouseItem(item) {
      return this.form.warehouse.subdistrict_id == item.subdistrict_id ? true : false
    },
    hasCodData(item) {
      let has =  this.form.cod_list.filter(en => en.subdistrict_id == item.subdistrict_id)
      if(has.length) {
        return true
      } else {
        return false
      }
    },
    removeCodList(index) {
      this.form.cod_list.splice(index, 1)
    },
    searchWarehouseData() {
      this.searchType = 'warehouse'
      this.findSubdistrict()
    },
    searchCodData() {
      this.searchType = 'cod'
      this.findSubdistrict()
    },
    getSeller() {
      Api().get('getSeller').then((response) => {
        if(response.status == 200){
          this.form.shop_name = response.data.results.name
          this.form.warehouse = response.data.results.warehouse
        }
      })
    },
    findSubdistrict() {
      this.subdistrictOptions = []
      if(this.search.length < 3) return
      this.searchLoading = true
      Api().get('shipping/findSubdistrict/'+ this.search)
      .then(response => {
        if(response.status == 200) {
          if(response.data.success) {

            this.subdistrictOptions = response.data.results

          } else {
            this.$q.notify({
              type: 'negative',
              message: response.data.message
            })
          }
        }
      })
      .finally(() => {
         this.searchLoading = false
      })
    },
    closeSubdistrictBox() {
      this.subdistrictOptions = []
      this.search = ''
    }
  }
}
</script>
