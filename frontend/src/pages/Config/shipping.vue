<template>
  <div>
    <q-form @submit.prevent="updateData">
      <q-card flat>
        <q-card-section>
          <div class="flex items-center justify-between">
            <div class="text-md text-weight-bold">Ekspedisi</div>
            <div class="q-px-sm rounded-borders text-white" :class="config && config.can_shipping? 'bg-green-7' : 'bg-grey-6'">{{ config && config.can_shipping? 'Active' : 'Disabled' }}</div>
          </div>
          <div class="text-caption text-grey-7">
            <div>Pengaturan ongkir otomatis by Rajaongkir</div>
            <div>Silahkan daftar di rajaongkir.com untuk mendapatkan apikey</div>
          </div>
          <div class="q-gutter-y-sm q-py-md">
            <q-select label="Raja Ongkir Tipe" filled :options="rajaongkirtypes" v-model="formdata.rajaongkir_type" @input="selectCourierType"></q-select>
            <q-input
            filled
            v-model="formdata.rajaongkir_apikey"
            label="Raja Ongkir API KEY"
            >
            </q-input>
          </div>
          <div v-if="config && config.is_shippable" class="q-mt-lg">
            <div class="text-grey-8 text-sm q-py-sm">Pengaturan Gudang Pengiriman</div>
              <div @click="changeWarehouse" class="cursor-pointer q-pa-md full-width border q-filled">{{ warehouseTitle() }}</div>
              <div class="q-mt-md" v-if="theCouriers.length">
                <div class="text-grey-8 text-sm q-py-sm">Pilih Kurir</div>
                  <div class="q-gutter-sm">
                    <q-btn unelevated rounded size="10px" v-for="(name, index) in theCouriers" :key="index" 
                    :color="isCourierActive(name)? 'green-5' : 'grey-5'" @click="handleSelectCourier(name)" :label="name"></q-btn>
                  </div>
              </div>
          </div>
        </q-card-section>
        <q-card-section v-if="config && config.is_shippable">
          <div>
            <div class="text-md text-weight-bold">Pengaturan COD</div>
            <div class="q-mb-sm text-caption text-grey-7">Pengaturan kecamatan tujuan untuk pengiriman COD <br>( Kosongkan untuk menonaktifkan ) </div>
            <div>
              <q-input filled :loading="searchLoading && searchType == 'cod'" placeholder="Ketik kecamatan tujuan COD" v-model="search" debounce="600" @input="searchCodData">
                <template slot="append" v-if="search">
                  <q-btn icon="close" flat @click="closeSubdistrictBox" color="red" round></q-btn>
                </template>
              </q-input>
              <div class="relative" v-if="searchType == 'cod' && subdistrictOptions.length">
                <transition
                  appear
                  enter-active-class="animated fadeIn"
                  leave-active-class="animated fadeOut"
                >
                <div class="absolute full-width z-30">
                  <q-card class="bg-white full-width full-height">
                    <div class="scroll" style="height:100%;max-height:300px;">
                      <q-list class="bg-grey-1">
                        <q-item clickable v-for="(itemData, index) in subdistrictOptions" :key="index" @click="selectCodItemData(itemData)">
                          <q-item-section side>
                            <template v-if="hasCodData(itemData)">
                            <q-icon name="remove_circle" size="17px" color="red"></q-icon>
                            </template>
                            <template v-else>
                            <q-icon name="add_circle" size="17px" color="green"></q-icon>
                            </template>
                        </q-item-section>
                          <q-item-section>
                            <q-item-label>{{ itemData.subdistrict_name }} - {{ itemData.type }} {{ itemData.city }}</q-item-label>
                          </q-item-section>
                        </q-item>
                      </q-list>
                    </div>
                      <q-inner-loading
                        :showing="searchLoading"
                      >
                      </q-inner-loading>
                  </q-card>
                </div>
                </transition>
              </div>
            </div>
            <div class="q-py-md">
              <q-list separator bordered dense>
                <q-item>
                  <q-item-section avatar>
                    #
                  </q-item-section>
                  <q-item-section>
                    Alamat
                  </q-item-section>
                  <q-item-section side>
                    Ongkos Kirim
                  </q-item-section>
                </q-item>
                <q-item v-for="(codItem, index) in formdata.cod_list" :key="index">
                  <q-item-section avatar>
                    <q-btn @click="removeCodList(index)" icon="close" color="red" round flat padding="5px" size="sm"></q-btn>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ codItem.subdistrict_name }} {{ codItem.type }} {{ codItem.city }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <div>
                    <q-input square outlined dense min="0" prefix="Rp" style="width:120px;"  mask="########" v-model="formdata.cod_list[index].price" required></q-input>
                    </div>
                  </q-item-section>
                </q-item>
              </q-list>
            </div>
          </div>
        </q-card-section>
        <q-card-actions class="q-pa-md justify-end">
          <q-btn type="submit" unelevated size="12px" label="Simpan Ekspedisi" color="blue-7"></q-btn>
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
      formdata: {
        rajaongkir_type: '',
        rajaongkir_apikey: '',
        warehouse_id: '',
        warehouse_address: null,
        rajaongkir_couriers: '',
        cod_list: []
      },
    }
  },
  computed: {
    courierHasSelect() {
      if(this.formdata.rajaongkir_couriers){
        return this.formdata.rajaongkir_couriers.split(',')
      } else {
        return []
      }
    },
    theCouriers() {
      if(this.formdata.rajaongkir_type == 'pro') {
        return this.config.courier_available.pro
      } else if(this.formdata.rajaongkir_type == 'basic'){
        return this.config.courier_available.basic
      } else {
        return this.config.courier_available.starter
      }
    },
    config: function() {
      return this.$store.state.config
    }
  },
  mounted() {
    this.getAdminConfig()
  },
  methods: {
    changeWarehouse() {
      this.modal = true
      setTimeout(() => {
        this.$refs.warehouse.focus()
      }, 300)
    },
    warehouseTitle() {
      if(this.formdata.warehouse_address) {
        return `${this.formdata.warehouse_address.subdistrict_name} - ${this.formdata.warehouse_address.type} ${this.formdata.warehouse_address.city}, ${this.formdata.warehouse_address.province}`
      }
      return 'Pilih Gudang Pengiriman'
    },
    setConfig(item) { 
      this.formdata.cod_list = item.cod_list? item.cod_list : []
      this.formdata.rajaongkir_type = item.rajaongkir_type
      this.formdata.rajaongkir_apikey = item.rajaongkir_apikey
      this.formdata.rajaongkir_couriers = item.rajaongkir_couriers
      this.formdata.warehouse_address = item.warehouse_address
      this.formdata.warehouse_id = item.warehouse_id
    },
    selectCourierType() {
      this.formdata.rajaongkir_couriers = ''
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
        this.formdata.rajaongkir_couriers = courierTemp.join(',')
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
      Api().post('config',  this.formdata).then(() => {
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
      this.formdata.warehouse_id = item.city_id
      this.formdata.warehouse_address = item
      this.modal = false
      this.subdistrictOptions = []
      this.search = ''
    },
    selectCodItemData(data) {
      let hasData = this.formdata.cod_list.filter(elj => elj.subdistrict_id == data.subdistrict_id)
      if(hasData.length) {
        this.formdata.cod_list = this.formdata.cod_list.filter(elm => elm.subdistrict_id != data.subdistrict_id)
      } else {
        }
      this.formdata.cod_list.push({...data, price: 0})
      this.search = ''
      this.subdistrictOptions = []
    },
    isInWarehouseItem(item) {
      return this.formdata.warehouse_address.subdistrict_id == item.subdistrict_id ? true : false
    },
    hasCodData(item) {
      let has =  this.formdata.cod_list.filter(en => en.subdistrict_id == item.subdistrict_id)
      if(has.length) {
        return true
      } else {
        return false
      }
    },
    removeCodList(index) {
      this.formdata.cod_list.splice(index, 1)
    },
    searchWarehouseData() {
      this.searchType = 'warehouse'
      this.findSubdistrict()
    },
    searchCodData() {
      this.searchType = 'cod'
      this.findSubdistrict()
    },
    getAdminConfig() {
      Api().get('adminConfig').then((response) => {
        if(response.status == 200){
          this.setConfig(response.data.results)
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
