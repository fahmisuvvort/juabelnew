<template>
  <q-page :class="{'flex flex-center' : !banks.available}">
     <q-header>
      <q-toolbar>
        <q-btn :to="{name: 'Settings'}"
          flat round dense
          icon="arrow_back" />
        <q-toolbar-title>
         Bank Account
        </q-toolbar-title>
        <q-btn class="gt-xs" no-caps flat icon="add_circle" @click="handleAdd" label="Tambah Akun"/>
      </q-toolbar>
    </q-header>
    <template v-if="banks.available">
     <div class="q-pt-md q-pb-xl">
       <table class="dense full-width">
         <tbody v-for="bank in banks.data" :key="bank.id">
           <tr>
             <td>
               <tr>
                <td>Nama Bank</td>
                <td>:</td>
                <td>{{ bank.bank_name }} - {{ bank.bank_office }}</td>
               </tr>
               <tr>
                <td>Nomor Rekening</td>
                <td>:</td>
                <td>{{ bank.account_number }}</td>
              </tr>
              <tr>
                <td>Nama Akun</td>
                <td>:</td>
                <td>{{ bank.account_name }}</td>
              </tr>
             </td>
             <td align="right">
                 <div class="text-grey-8 column q-gutter-y-sm items-center">
                  <q-btn @click="remove(bank.id)" size="sm" round icon="delete" glossy color="red"/>
                  <q-btn @click="edit(bank)" size="sm" round glossy color="info" icon="edit" />
                </div>
             </td>
           </tr>
         </tbody>
       </table>
    </div>
    </template>
    <template v-else>
      <div>Tidak ada data</div>
    </template>
     <q-inner-loading :showing="!banks.ready">
        <q-spinner-facebook size="50px" color="primary"/>
    </q-inner-loading>
    <q-page-sticky class="lt-sm" position="bottom-right" :offset="[18, 18]">
      <q-btn fab icon="add" color="primary" @click="handleAdd" glossy/>
    </q-page-sticky>
    <q-dialog v-model="modal">
      <q-card style="width:100%;max-width:400px;">
        <q-form @submit.prevent="submit">
          <q-card-section>
            <div class="text-weight-bold text-md">{{ formType == 'add' ? 'Tambah ' : 'Edit ' }} Data Bank</div>
            <q-input
              v-model="form.bank_name"
              label="Nama Bank"
              :rules="[val => val && val.length > 0 || 'Wajib Diisi']"
              placeholder="BCA"
             />
            <q-input
              v-model="form.bank_office"
              label="Kantor Cabang"
              :rules="[val => val && val.length > 0 || 'Wajib Diisi']"
              placeholder="Yogyakarta"
             />
            <q-input
              v-model="form.account_name"
              label="Nama Akun"
              :rules="[val => val && val.length > 0 || 'Wajib Diisi']"
             />
            <q-input
              v-model="form.account_number"
              label="Nomor Rekening"
              :rules="[val => val && val.length > 0 || 'Wajib Diisi']"
              placeholder="6985XXXXXXXXXXXX"
             />
          </q-card-section>
          <q-card-actions class="justify-end">
            <q-btn :disabled="loading" flat label="Batal" type="button" color="primary" @click.prevent="closeModal"></q-btn>
            <q-btn :loading="loading" unelevated label="Simpan" type="submit" color="primary"></q-btn>
          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'BankIndex',
  data() {
    return {
      formType: '',
      modal: false,
      form: {
        id: '',
        _method: 'POST',
        bank_name: '',
        bank_office: '',
        account_name: '',
        account_number: ''
      }
    }
  },
  computed: {
    ...mapState({
      banks: state => state.bank.banks,
      loading: state => state.loading
    })
  },
  methods: {
    ...mapActions('bank', ['getBanks', 'destroyBank', 'updateBank', 'storeBank']),
    submit() {
      if(this.loading) return
      if(this.formType == 'add') {
        this.storeBank(this.form)
        this.closeModal()
      }
      if(this.formType == 'edit') {
        this.updateBank(this.form)
        this.closeModal()
      }
    },
    remove(id) {
      this.$q.dialog({
        title: 'Konfirmasi Penghapusan Item',
        message: 'Yakin akan menghapus data?',
        ok: {label: 'Hapus', flat: true, 'no-caps': true},
        cancel: {label: 'Batal', flat: true, 'no-caps': true},
      }).onOk(() => {
        this.destroyBank(id)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
      })
    },
    handleAdd() {
      this.formType = 'add'
      this.modal = true
      this.form._method = 'POST'
    },
    edit(bank) {
      this.setData(bank)
      this.formType = 'edit'
      this.form._method = 'PUT'
      this.modal = true
    },
    setData(data) {
      this.form.bank_name = data.bank_name
      this.form.bank_office = data.bank_office
      this.form.account_name = data.account_name
      this.form.account_number = data.account_number
      this.form.id = data.id
    },
    closeModal() {
      this.modal = false
      this.clearData()
    },
    clearData() {
      this.form.bank_name = ''
      this.form.bank_office = ''
      this.form.account_name = ''
      this.form.account_number = ''
      this.form.id = ''
    }
  },
  created() {
    if(!this.banks.data.length) this.getBanks()
  }
}
</script>
