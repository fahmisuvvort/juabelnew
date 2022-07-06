<template>
  <q-page>
     <q-header>
      <q-toolbar>
        <q-btn v-go-back.single
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         List User
        </q-toolbar-title>
      </q-toolbar>
      </q-header>
      <div class="q-pa-md row justify-between items-center q-gutter-sm">
        <div class="col">
          <q-input square v-model="search" outlined dense @input="findUser" debounce="1000" placeholder="Ketik nama, email atau nomor telpon user">
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
            <q-item-section>Info User</q-item-section>
            <q-item-section>Role</q-item-section>
            <q-item-section side>Aksi</q-item-section>
          </q-item>
          <q-item v-for="(user, i) in users" :key="i">
            <q-item-section side top>{{ i+1 }}</q-item-section>
            <q-item-section top>
              <div>{{ user.name }}</div>
              <div>{{ user.email }}</div>
              <div>{{ user.phone }}</div>
            </q-item-section>
            <q-item-section>
                <div>{{ user.role }}</div>
            </q-item-section>
            <q-item-section side class="q-gutter-sm">
              <q-btn size="sm" color="red-6" round unelevated icon="delete" @click="handleDeleteUser(user.id)">
                <q-tooltip>
                  Hapus User
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
          <q-btn :loading="load" v-if="canLoad" label="Loadmore" @click="loadUser" color="primary" outline></q-btn>
        </div>
      </div>
  </q-page>
</template>

<script>
import { Api } from 'boot/axios'
export default {
  name: 'UserList',
  data() {
    return {
      users: [],
      take: 10,
      canLoad: false,
      search: '',
      userNotAvailable: false,
      deleteId: null,
      load: false
    }
  },
  mounted() {
    if(this.users.length < this.take) {
      this.getUsers()
    }
  },
  computed: {
    isDesktop() {
      return window.innerWidth > 600 ? true : false
    }
  },
  methods: {
    getUsers() {
      this.$q.loading.show()
      Api().get(`userList?take=${this.take}&skip=${this.users.length}`).then(response => {
        if(response.status == 200) {
          this.users = response.data.results
          this.canLoad = response.data.results.length == this.take ? true : false
          this.userNotAvailable = this.users.length ? false : true
        }
      })
      .finally(() => {
        this.$q.loading.hide()
      })
    },
    loadUser() {
      this.load = true
      Api().get(`userList?take=${this.take}&skip=${this.users.length}`).then(response => {
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
    findUser() {
      if(this.search) {
        Api().get('findUser/' + this.search).then(response => {
          if(response.status == 200) {
            this.users = response.data.results
            this.userNotAvailable = this.users.length ? false : true
          }
        })
      }
    },
    reset() {
      this.users = []
      this.search = ''
      this.getUsers()
    },
    handleDeleteUser(id) {
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
        this.deleteUser(id)
      })
    },
    handleDisableVendor(id) {
      this.$q.dialog({
        title: 'Yakin akan NON aktifkan toko?',
        cancel: true
      }).onOk(() => {
        this.disableVendor(id)
      })
    },
    handleEnableVendor(id) {
      this.$q.dialog({
        title: 'Yakin akan mengaktifkan toko?',
        cancel: true
      }).onOk(() => {
        this.enableVendor(id)
      })
    },
    disableVendor(id) {
      Api().post('setVendorStatus', { id: id, status: 'disable'}).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
    },
    enableVendor(id) {
      Api().post('setVendorStatus', { id: id, status: 'enable'}).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
    },
    deleteUser(id) {
      this.$q.loading.show()
      Api().delete('user/'+ id).then(response => {
        if(response.status == 200) {
          this.reset()
        }
      })
      .finally(() => {
        this.$q.loading.hide()
      })
    },
  }
}
</script>