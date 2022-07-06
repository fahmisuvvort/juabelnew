<template>
  <q-card flat>
     <q-card-section>
      <div class="text-md text-weight-bold">Themes</div>
      <div class="text-caption text-grey-7">Pengaturan tema dan warna dasar situs</div>
    </q-card-section>
    <q-form @submit.prevent="updateData">
      <q-list>
        <q-item>
          <q-item-section>
            <q-item-label>Tema</q-item-label>
          </q-item-section>
          <q-item-section side>
              <q-select outlined dense v-model="form.theme" :options="themes"></q-select>
          </q-item-section>
        </q-item>
        <q-item>
          <q-item-section>
            <q-item-label>Warna</q-item-label>
          </q-item-section>
          <q-item-section side>
              <input ref="color" type="color" v-model="form.theme_color" style="width:110px;height:20px;"/>
          </q-item-section>
        </q-item>
      </q-list>
      <q-card-section class="flex justify-end">
        <q-btn unelevated size="12px" type="submit" label="Simpan Tema" color="blue-7"></q-btn>
      </q-card-section>
    </q-form>
  </q-card>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  data () {
    return {
      form: {
        theme: '',
        theme_color: ''
      }
    }
  },
  computed: {
    themes() {
      return this.$store.state.themes
    },
    config: function() {
      return this.$store.state.config
    }
  },
   watch: {
    'form.theme_color': function(val) {
      this.setColor(val)
    }
  },
  mounted() {
    if(this.config) {
      this.form.theme = this.config.theme
      this.form.theme_color = this.config.theme_color
    }
  },
  methods: {
    updateData(){
      let data = {...this.config, ...this.form}
      Api().post('config', this.form).then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Berhasil menyimpan pengaturan'
        })
       this.$store.dispatch('getConfig')
      })
    },
    handleChangeColor() {
      this.$refs.color.click()
    },
    setColor(clr) {
      this.$store.commit('SET_THEME_COLOR', clr)
    }
  }
}
</script>

<style>

</style>