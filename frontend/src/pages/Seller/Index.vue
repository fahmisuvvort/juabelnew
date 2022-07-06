<template>
  <q-page>
     <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List Seller
        </q-toolbar-title>
        <q-btn icon="add_circle" label="Seller" @click="handleAddSeller" flat></q-btn>
      </q-toolbar>
      </q-header>
      <div class="q-pa-md row justify-between items-center q-gutter-sm">
        <div class="col">
          <q-input square v-model="search" outlined dense @input="findSeller" debounce="1000" placeholder="Ketik nama, email atau nomor telpon user">
          </q-input>
        </div>
        <div class="col-auto" style="width:60px">
          <q-input square v-model="take" outlined dense  label="Show" type="number" min="4"></q-input>
        </div>
        <div class="col-auto">
          <q-btn label="reset" unelevated color="primary" @click="reset"></q-btn>
        </div>
      </div>
      <div>
        <q-list separator>
          <q-item>
            <q-item-section side>#</q-item-section>
            <q-item-section>Info Seller</q-item-section>
            <q-item-section side>Aksi</q-item-section>
          </q-item>
          <q-item v-for="(user, i) in users" :key="i">
            <q-item-section side top>{{ i+1 }}</q-item-section>
            <q-item-section top>
              <div>{{ user.name }}</div>
              <div>{{ user.email }}</div>
              <div>{{ user.phone }}</div>
            </q-item-section>
            <q-item-section side class="q-gutter-sm">
              <q-btn size="sm" color="blue" round unelevated icon="edit" @click="handleEditSelller(user)">
                <q-tooltip>
                  Edit seller
                </q-tooltip>
              </q-btn>
              <q-btn size="sm" color="red-6" round unelevated icon="delete" @click="handleDeleteSeller(user.id)">
                <q-tooltip>
                  Hapus Seller
                </q-tooltip>
              </q-btn>
            </q-item-section>
          </q-item>
          <q-item v-if="userNotAvailable">
            <q-item-section class="text-center q-py-md">
              Tidak ada data
            </q-item-section> 
          </q-item>
        </q-list>
        <div class="q-py-md flex justify-center">
          <q-btn :loading="load" v-if="canLoad" label="Loadmore" @click="loadSeller" color="primary" outline></q-btn>
        </div>
      </div>
    <q-dialog v-model="addModal">
      <q-card class="card-medium">
        <div class="card-heading">{{ form.type }} Seller</div>
        <q-card-section>
          <q-form class="q-gutter-y-md" @submit.prevent="submit">
            <q-input v-model="form.name" filled required label="Nama Seller"></q-input>
            <q-input v-model="form.email" filled required label="Email seller"></q-input>
            <q-input v-model="form.phone" filled required label="Ponsel Seller"></q-input>
            <q-input v-model="form.shop_name" filled required label="Nama Toko"></q-input>
            <div>
              <q-input :type="isPwd? 'password' : 'text'" v-model="form.password" filled required label="Password">
                <template v-slot:append>
                  <q-icon :name="isPwd? 'visibility' : 'visibility_off'" @click="isPwd = !isPwd" class="cursor-pointer"></q-icon>
                </template>
              </q-input>
              <div class="text-xs text-amber-8" v-if="form.type == 'Edit'">Kosongkan jika tidak ganti password</div>
            </div>
            <q-select label="Role" v-model="form.role" filled required v-if="form.type == 'Edit'" :options="roles"></q-select>
            <div class="flex justify-end q-pt-md">
              <q-btn label="Batal" v-close-popup flat color="primary"></q-btn>
              <q-btn :loading="loading" label="Submit" type="submit" color="primary" unelevated></q-btn>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'SellerIndex',
  data() {
    return {
      users: [],
      take: 10,
      canLoad: false,
      search: '',
      userNotAvailable: false,
      deleteId: null,
      addModal: false,
      load: false,
      isPwd: true,
      roles: ['admin', 'seller', 'customer'],
      form: {
        id: '',
        type: 'Tambah',
        name: '',
        shop_name: '',
        email: '',
        phone: '',
        role: '',
        password: '',
      },
    }
  },
  mounted() {
    if(this.users.length < this.take) {
      this.getSeller()
    }
  },
  computed: {
    isDesktop() {
      return window.innerWidth > 600 ? true : false
    },
    loading() {
    return this.$store.state.loading
    }
  },
  methods: {
    handleAddSeller() {
      this.form.type = 'Tambah'
      this.clearForm()
      this.addModal = true
    },
    handleEditSelller(v) {
      this.form.type = 'Edit'
      this.form.id = v.id
      this.form.name = v.name
      this.form.email = v.email
      this.form.role = v.role
      this.form.password = v.password
      this.form.phone = v.phone
      this.addModal = true
    },
    clearForm() {
      this.form.name = ''
      this.form.phone = ''
      this.form.email = ''
      this.form.password = ''
    },
    getSeller() {
      this.$q.loading.show()
      Api().get(`sellers?take=${this.take}&skip=${this.users.length}`).then(response => {
        if(response.status == 200) {
          this.users = response.data.results
          console.log(this.users);
          this.canLoad = response.data.results.length == this.take ? true : false
          this.userNotAvailable = this.users.length ? false : true
        }
      })
      .finally(() => {
        this.$q.loading.hide()
      })
    },
    loadSeller() {
      this.load = true
      Api().get(`sellers?take=${this.take}&skip=${this.users.length}`).then(response => {
        if(response.status == 200) {
          this.users = [...this.users, ...response.data.results]
          this.canLoad = response.data.results.length == this.take ? true : false
          this.userNotAvailable = this.users.length ? false : true
        }
      })
      .finally(() => {
        this.load = false
      })
    },
    findSeller() {
      if(this.search) {
        Api().get('findSeller/' + this.search).then(response => {
          if(response.status == 200) {
            this.users = response.data.results
            this.userNotAvailable = this.users.length ? false : true
          }
        })
      }
    },
    submit() {
      this.$store.commit('SET_LOADING', true)
      if(this.form.type == 'Tambah') {  
        this.form._method = 'POST'
        this.$store.dispatch('seller/storeSeller', this.form).then(() => {
          this.addModal = false
          this.getSeller()
        })
        .finally(() => {
         this.$store.commit('SET_LOADING', false)
        })
      }
      if(this.form.type == 'Edit') {  
        this.form._method = 'PUT'
        this.$store.dispatch('seller/updateSeller', this.form).then(() => {
          this.addModal = false
          this.getSeller()
          this.form.id = ''
        })
        .finally(() => {
         this.$store.commit('SET_LOADING', false)
        })
      }
    },
    reset() {
      this.users = []
      this.search = ''
      this.getSeller()
    },
    handleDeleteSeller(id) {
      if(id == 1) {
        this.$q.notify({
          type: 'negative',
          message: 'User admin tidak bisa di hapus'
        })
        return
      }
      this.$q.dialog({
        title: 'Yakin akan menghapus user?',
        message: 'Data yang di hapus tidak dapat dikembalikan',
        cancel: true
      }).onOk(() => {
        this.deleteSeller(id)
      })
    },
    handleDisableSeller(id) {
      this.$q.dialog({
        title: 'Yakin akan NON aktifkan toko?',
        cancel: true
      }).onOk(() => {
        this.disableSeller(id)
      })
    },
    handleEnableSeller(id) {
      this.$q.dialog({
        title: 'Yakin akan mengaktifkan toko?',
        cancel: true
      }).onOk(() => {
        this.enableSeller(id)
      })
    },
    disableSeller(id) {
      Api().post('setSellerStatus', { id: id, status: 'disable'}).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
    },
    enableSeller(id) {
      Api().post('setSellerStatus', { id: id, status: 'enable'}).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
    },
    deleteSeller(id) {
      this.$store.commit('SET_LOADING', true)
      Api().delete('user/'+ id).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
      .finally(() => {
        this.$store.commit('SET_LOADING', false)
      })
    },
  }
}
</script>