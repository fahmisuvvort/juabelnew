<template>
   <q-page class="q-pb-xl" :class="{'flex flex-center' : !sliders.available}">
    <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Slider Banner
        </q-toolbar-title>
        <q-btn class="gt-xs" flat icon="add_circle" label="Tambah Slider" no-caps @click="handleBtnUpload"/>
      </q-toolbar>
    </q-header>
    <div class="q-pa-sm text-xs text-grey-8 bg-yellow-2" :class="sliders.data.length? 'relative' : 'absolute-top'">
      <div>Note</div>
      <div>Untuk hasil terbaik, Gunakan gambar dengan ukuran yang sama.</div>
    </div>
      <input type="file" class="hidden" ref="image" @change="handleFileUpload">
        <!-- <progress max="100" :value="uploadPercentage" class="full-width" style="height:20px;" v-show="uploadPercentage > 0"></progress> -->
      <template v-if="sliders.available">
      <div class="q-py-md">
        <q-list separator>
        <q-item v-for="slider in sliders.data" :key="slider.id">
          <q-item-section top avatar>
            <img :src="slider.src" class="shadow-4 q-pa-xs bg-white" style="width:100px;height:70px;object-fit:cover;"/>
          </q-item-section>
          <q-item-section top>
            <div class="q-mb-xs">Urutan Tamplian</div>
            <div class="row q-gutter-sm items-center">
              <!-- <q-input style="max-width:4rem;" dense outlined type="number" :value="slider.weight" @input="changeWeight($event,slider.id)"></q-input> -->
              <q-btn icon="remove" size="xs" unelevated round class="bg-blue-grey-1" @click="decre(slider)" />
              <span>{{ slider.weight }}</span>
              <q-btn icon="add" size="xs" round unelevated class="bg-blue-grey-1" @click="incre(slider)" />
            </div>
          </q-item-section>
          <q-item-section center side>
            <q-btn @click="remove(slider.id)" size="sm" round icon="delete" glossy color="red"/>
          </q-item-section>
        </q-item>
      </q-list>
      </div>
      </template>
      <template v-else>
        <div>Tidak ada data</div>
      </template>
    <q-inner-loading :showing="!sliders.ready">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
      <q-btn fab icon="add" color="primary" glossy @click="handleBtnUpload"/>
    </q-page-sticky>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
import { mapState, mapActions } from 'vuex'
export default {
  data () {
    return {
      timeout: null,
      uploadPercentage: 0
    }
  },
  computed: {
    ...mapState({
      sliders: state => state.slider.sliders,
      loading: state => state.loading
    })
  },
  mounted() {
    if(!this.sliders.data.length) this.getSliders()
  },
  methods: {
    ...mapActions('slider',['removeSlider','getSliders', 'updateSliderWeight']),

    changeWeight(weight, id) {
      
      this.updateSliderWeight({ value: weight, id: id })
        
    },

    incre(slider) {
      this.changeWeight(slider.weight+1, slider.id)
      // if (this.timeout) clearTimeout(this.timeout); 
      // this.timeout = setTimeout(() => {
      //   }, 1000); // delay
    },
    decre(slider) {
      if(slider.weight <=1) return
        this.changeWeight(slider.weight-1, slider.id)

      // if (this.timeout) clearTimeout(this.timeout); 
      // this.timeout = setTimeout(() => {
      //   }, 100); // delay
    },

    remove(id) {
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data ini?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.removeSlider(id)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
    handleBtnUpload() {
      this.$refs.image.click()
    },
    handleFileUpload(){
      let self = this;
      self.$store.commit('slider/SET_DATA_STATUS', false)
      let formData = new FormData();
      formData.append('image', this.$refs.image.files[0]);

      Api().post('sliders',
        formData,
        {
          headers: {'Content-Type': 'multipart/form-data' },
          onUploadProgress: (progressEvent) => {
                this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
              }
        }).then(function(res){
          self.getSliders()
        })
        .catch(function(err){
          console.log('FAILURE!!');
           self.$store.commit('slider/SET_DATA_STATUS', true)
        });
      },
    }
}
</script>

<style lang="css" scoped>
.absolute-top-right {
  top:4px;
  right:4px;
  }
</style>>