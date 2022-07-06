<template>
  <q-card flat square class="max-width bg-white" :class="{'flex flex-center': !variants.length}">
    <q-header class="max-width">
      <q-toolbar>
        <q-toolbar-title>Produk Variasi</q-toolbar-title>
        <div class="q-gutter-x-sm">
          <q-btn unelevated label="Simpan" icon="save" @click="$emit('onSubmitForm', variants)"></q-btn>
          <q-btn unelevated label="Batal" icon="close" @click="$emit('onClose')"></q-btn>
        </div>
      </q-toolbar>
    </q-header>
    <q-form @submit.prevent="submit" class="q-pt-xl">
      <div id="variants" v-if="variants.length" style="min-height:200px;" class="q-pb-xl">
        <div v-for="(variant, varIndex) in variants" :key="varIndex" class="q-pa-md">
          <div class="row items-center justify-between">
              <div class="row items-center q-gutter-x-sm">
              <div class="text-md2 text-weight-medium">Varian {{ variant.variant_name }} </div>
              <q-btn no-caps padding="4px 8px" unelevated size="13px" color="primary" @click="handleAddVariantItem(varIndex)">
                <q-icon name="add"></q-icon>
                <span>{{ 'Varian ' +variant.variant_name }}</span>
              </q-btn>
            </div>
            <div class="q-gutter-x-sm">
              <q-btn flat round dense icon="close" unelevated color="red-6" @click="handleRemoveVariant(varIndex)"></q-btn>
            </div>
          </div>
          <div v-for="(item, itemIndex) in variant.variant_items" :key="itemIndex" class="bg-grey-2 q-mt-md q-pa-sm">
            <div class="row justify-between items-center q-pa-xs ">
              <div class="text-md2 text-weight-medium">{{ variant.variant_name }} {{ item.variant_item_label }}</div>
              <div class="q-gutter-x-sm">
                <q-btn no-caps padding="4px 8px" unelevated size="12px" color="blue-6" @click="handleAddVariantValue(varIndex, itemIndex)">
                    <q-icon name="add"></q-icon>
                    <span>Tambah {{ variant.variant_item_name }}</span>
                </q-btn>
              </div>
            </div>
            <q-list class="bg-white q-pa-sm">
              <q-item  v-for="(subItem, subItemIndex) in item.variant_item_values" :key="subItemIndex" class="q-pa-sm">
                <q-item-section>
                  <q-input stack-label filled square required v-model="variants[varIndex].variant_items[itemIndex].variant_item_values[subItemIndex].item_label" dense :label="variant.variant_item_name"></q-input>
                </q-item-section>
                <q-item-section>
                  <q-input stack-label filled square required v-model="variants[varIndex].variant_items[itemIndex].variant_item_values[subItemIndex].additional_price" dense label="Additional Price" type="number" min="0"></q-input>

                </q-item-section>
                <q-item-section>
                  <q-input stack-label filled square required v-model="variants[varIndex].variant_items[itemIndex].variant_item_values[subItemIndex].item_stock" dense label="Stok" type="number" min="0"></q-input>

                </q-item-section>
                <q-item-section side>
                  <q-btn flat icon="close" unelevated dense round color="red-6" @click="handleRemoveVariantValue(varIndex, itemIndex, subItemIndex)"></q-btn>
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </div>
      </div>
    </q-form>
    <div class="text-center q-gutter-y-sm" v-if="!variants.length">
      <div class="text-md">Tidak ada produk variasi</div>
      <q-btn label="Tambah Produk Variasi" color="primary" unelevated @click="handleAddVariant"></q-btn>
    </div>
    <q-dialog v-model="variantModal">
        <q-card class="card-medium">
          <q-form @submit.prevent="addVariant">
            <div class="card-heading">Tambah Produk Varian</div>
            <q-card-section>
              <q-input required label="Nama Varian" v-model="tempVariant"  hint="contoh: Warna"></q-input>
              <q-input required label="Nama Sub Varian" v-model="tempVariantItem"  hint="contoh: Ukuran"></q-input>
              <div class="row justify-end q-mt-md q-gutter-x-sm">
                <q-btn type="button" label="Batal" unelevated color="primary" outline v-close-popup></q-btn>
                <q-btn type="submit" label="Tambah" unelevated color="primary"></q-btn>
              </div>
            </q-card-section>
          </q-form>
        </q-card>
      </q-dialog>
      <q-dialog v-model="variantItemModal">
        <q-card class="card-medium">
          <q-form @submit.prevent="addVariantItem">
            <div class="card-heading">Tambah varian {{ variantSelectedLabel }}</div>
            <q-card-section>
              <q-input :label="variantSelectedLabel" required v-model="tempVarianItem"></q-input>
              <q-input :label="variantItemSelectedLabel" required v-model="tempVarianValue"></q-input>
              <div class="text-sm text-grey-7 q-pt-sm">{{ variantItemSelectedLabel }} untuk varian {{ variantSelectedLabel }}, gunakan tanda koma (,) untuk multiple varian</div>
              <div class="row justify-end q-mt-md q-gutter-x-sm">
                <q-btn type="button" label="Batal" unelevated color="primary" outline v-close-popup></q-btn>
                <q-btn type="submit" label="Tambah" unelevated color="primary"></q-btn>
              </div>
            </q-card-section>
          </q-form>
        </q-card>
      </q-dialog>
  </q-card>
</template>

<script>
export default {
  name: 'VariationForm',
  props: ['props'],
  data() {
    return {
      variants: this.props || [],
      variantModal: false,
      tempVariant: '',
      tempVariantItem: '',
      tempVarianValue: '',
      variantItemModal: false,
      tempVarianItem: '',
      variantSelectedIndex: null
    }
  },
  methods: {
    handleRemoveVariant(varIndex) {
      this.variants.splice(varIndex, 1)
    },
    handleRemoveVariantItem(varIndex, itemIndex) {
      this.variants[varIndex].variant_items.splice(itemIndex, 1)
    },
    handleRemoveVariantValue(varIndex, itemIndex, subItemIndex) {
      this.variants[varIndex].variant_items[itemIndex].variant_item_values.splice(subItemIndex, 1)
      if(this.variants[varIndex].variant_items[itemIndex].variant_item_values.length < 1) {
        this.variants[varIndex].variant_items.splice(itemIndex, 1)
      }
    },
    handleAddVariantValue(varIndex, itemIndex) {
      this.variants[varIndex].variant_items[itemIndex].variant_item_values.push(
        {item_sku: this.getSku(), item_label: '', additional_price: 0, item_stock: 0},
      )
    },
    handleAddVariant() {
        this.tempVariant = ''
        this.tempVariantItem = ''
        this.variantModal = true
    },
    handleAddVariantItem(varIndex) {
        this.tempVariant = ''
        this.tempVariantItem = ''
        this.tempVarianValue = ''
        this.variantItemModal = true
        this.variantSelectedIndex = varIndex
    },
    addVariant() {
      let tpl = { variant_name: this.tempVariant, variant_item_name: this.tempVariantItem, variant_items: [] }
      this.variants.push(tpl)
      this.tempVariant = ''
      this.tempVariantItem = ''
      this.tempVarianValue = ''
      this.variantModal = false
    },
    addVariantItem() {
      let tpl = {
            variant_item_label: this.tempVarianItem, 
            variant_item_values: []
          }
      let arrItem = this.tempVarianValue.split(',');

      arrItem.forEach(el => {
          tpl.variant_item_values.push(
            {item_sku: this.getSku(), item_label: el.trim(), additional_price: 0, item_stock: 0},
          )
      });

      this.variants[this.variantSelectedIndex].variant_items.push(tpl)
      this.variantSelectedIndex = null
      this.tempVarianItem = ''
      this.variantItemModal = false
    },
    getSku() {
      var randomChars = 'ABCDEFGHIJKL9MNOPQRST8UVWXYZabcdeAfghijklmnopqZrstuvwxyz01T2343567hD89';

      var result = 'VAR';
        for ( var i = 0; i < 17; i++ ) {
            result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }
      return result;
    },
  },
  computed: {
    variantItemSelected() {
      if(this.variantSelectedIndex != '' || this.variantSelectedIndex != null) {
        return this.variants.find((el, index) => index == this.variantSelectedIndex)
      }
      return null
    },
    variantSelectedLabel() {
      if(this.variantItemSelected) {
        return this.variantItemSelected.variant_name
      }
      return ''
    },
    variantItemSelectedLabel() {
      if(this.variantItemSelected) {
        return this.variantItemSelected.variant_item_name
      }
      return ''
    }
  }
}
</script>