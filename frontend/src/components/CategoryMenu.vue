<template>
  <div class="bg-white max-width">
    <q-list separator>
      <q-item>
        <q-item-section side>
          <q-icon name="apps"></q-icon>
        </q-item-section>
        <q-item-section>
          <q-item-label class="text-md">Kategori</q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-btn flat icon="close" dense @click="closeCategory"></q-btn>
        </q-item-section>
      </q-item>
      <q-item v-for="category in categories.data" :key="category.id" clickable @click="handleShowCategory(category.id)">
        <q-item-section avatar>
          <q-avatar>
            <q-img :src="category.src"></q-img>
          </q-avatar>
        </q-item-section>
        <q-item-section>
          <q-item-label>{{ category.title }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
    </div>
</template>

<script>
export default {
  computed: {
    categories() {
      return this.$store.state.category.categories
    }
  },
  methods: {
    handleShowCategory(id) {
      this.closeCategory()
      if(id != this.$route.params.id) {
        this.$store.dispatch('product/getProductsByCategory', id)
        this.$router.push({ name: 'ProductCategory', params: { id: id }})
      }
    },
    closeCategory() {
      this.$store.commit('SET_MENU_CATEGORY', false)
    }
  },
  mounted() {
    if(!this.categories.data.length) {
      this.$store.dispatch('category/getCategories')
    }
  }
}
</script>
