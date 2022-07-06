<template>
  <q-list separator>
    <q-item clickable @click.native="backButtonCategory" v-if="parent" class="sticky-top z-20 bg-grey-3">
      <q-item-section>
        <div class="flex items-center q-gutter-x-sm">
          <q-icon dense size="16" name="arrow_back"></q-icon>
          <q-item-label>Kembali</q-item-label>
        </div>
      </q-item-section>
    </q-item>
    <q-item v-if="!parent" class="sticky-top z-20 bg-grey-3">
      <q-item-section>Pilih Kategori</q-item-section>
    </q-item>
    <q-item v-if="parent && selectable" class="sticky-top z-20 bg-grey-3 cursor-pointer">
      <q-item-section @click.native="insertParent">
       <q-item-label>{{ parent.title }}  <span class="text-grey-6" style="font-size:13px;"> (root category)</span></q-item-label>
      </q-item-section>
    </q-item>
    <q-item v-for="cat in current" :key="cat.id">
       <q-item-section avatar v-if="cat.src">
        <q-img :src="cat.src"></q-img>
      </q-item-section>
      <q-item-section  @click.native="selectCategory(cat)" class="cursor-pointer">
       <q-item-label>{{ cat.title }}</q-item-label>
      </q-item-section>
      <q-item-section side v-if="editable">
        <div class="text-grey-8 q-gutter-sm">
          <q-btn @click="editButton(cat)" size="sm" round icon="edit" glossy color="info"/>
          <q-btn @click="removeButton(cat.id)" size="sm" round icon="delete" glossy color="red"/>
        </div>
      </q-item-section>
    </q-item>
  </q-list>
</template>

<script>
export default {
  name: 'CategoryBlock',
  props: {
    categories: Array,
    editable: {
      type: Boolean,
      default: false
    },
    selectable: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      removeId: null,
      parent: null,
      current: []
    }
  },
  mounted() {
    this.getRootCategory()
  },
  watch: {
    categories() {
      this.getRootCategory()
    }
  },
  methods: {
    getRootCategory() {
      this.parent = null
      this.current = this.categories.filter(c => !c.category_id)
    },
    selectParentCategory() {
      if(this.parent.category_id) {
        this.current = this.categories.filter(el => el.category_id == this.parent.category_id)
        this.getParentCategory(this.parent.category_id)

      } else {
        this.getRootCategory()
      }
    },
    insertParent() {
      this.$emit('onSelect', this.parent)
    },
    selectCategory(cat) {
      this.parent = cat
      let childs = this.categories.filter(f => f.category_id == cat.id)

        if(childs.length) {
          this.current = childs
        } else {
           if(this.selectable) {
            this.$emit('onSelect', this.parent)
           } else {
             return
           }
        }
      
    },
    getParentCategory(id) {
      this.parent =  this.categories.find(c => c.id == id)
    },
    backButtonCategory() {
      if(!this.parent) {
        this.$emit('onClose')
      } else {
        this.selectParentCategory()
      }
    },
    clearCategorySelected() {
      this.parent = null
      // this.$emit('onClear')

    },
    editButton(cat) {
      this.getParentCategory(cat.category_id) 
      this.$emit('onEdit', {category: cat, parent: this.parent})
    },
    removeButton(id) {
      this.$emit('onRemove', id)
    }
  }
}
</script>
