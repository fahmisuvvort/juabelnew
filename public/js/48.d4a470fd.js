(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[48],{"826c":function(a,e,t){"use strict";t.r(e);var i=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("q-page",{staticClass:"q-pb-xl"},[t("q-header",[t("q-toolbar",[t("q-btn",{directives:[{name:"go-back",rawName:"v-go-back.single",modifiers:{single:!0}}],attrs:{flat:"",round:"",dense:"",icon:"arrow_back"}}),t("q-toolbar-title",[a._v("\n       Tambah Produk\n      ")])],1)],1),t("q-form",{on:{submit:function(e){return e.preventDefault(),a.submit(e)}}},[t("div",{staticClass:"q-pa-md q-gutter-y-sm"},[t("q-input",{attrs:{type:"text",label:"Nama Produk",rules:a.requiredRules},model:{value:a.form.title,callback:function(e){a.$set(a.form,"title",e)},expression:"form.title"}}),a.errors.title?t("div",{staticClass:"text-xs text-red"},[a._v(" "+a._s(a.errors.title[0]))]):a._e(),t("div",{staticClass:"row items-center q-gutter-x-sm"},[t("div",{staticClass:"col"},[t("q-input",{attrs:{mask:"#########",label:"Harga",rules:a.requiredRules},model:{value:a.form.price,callback:function(e){a.$set(a.form,"price",e)},expression:"form.price"}})],1),t("div",{staticClass:"col"},[t("q-input",{attrs:{mask:"#####",label:"Stok",rules:a.requiredRules},model:{value:a.form.stock,callback:function(e){a.$set(a.form,"stock",e)},expression:"form.stock"}})],1),t("div",{staticClass:"col"},[t("q-input",{attrs:{mask:"#####",label:"Berat",suffix:"gram",rules:[function(a){return a&&a>49||"Berat harus diisi min 50 gram"}]},model:{value:a.form.weight,callback:function(e){a.$set(a.form,"weight",e)},expression:"form.weight"}})],1)]),t("div",[a.categorySelected?a._e():t("q-input",{attrs:{readonly:"",label:"Pilih Kategori"},on:{click:function(e){a.categoryModal=!0}},scopedSlots:a._u([{key:"append",fn:function(){return[t("q-btn",{attrs:{flat:"",dense:"",round:"",icon:"edit"},on:{click:function(e){a.categoryModal=!0}}})]},proxy:!0}],null,!1,3058586195)}),a.categorySelected?t("q-input",{attrs:{readonly:"",value:a.categorySelected.title,label:"Kategori"},scopedSlots:a._u([{key:"append",fn:function(){return[t("q-btn",{attrs:{flat:"",dense:"",round:"",icon:"close"},on:{click:a.clearCategory}})]},proxy:!0}],null,!1,579244557)}):a._e()],1),t("div",{staticClass:"q-mt-md q-mb-sm"},[t("label",{staticClass:"text-grey-7 q-pb-sm block",attrs:{for:"description"}},[a._v("Deskripsi")]),t("q-editor",{model:{value:a.form.description,callback:function(e){a.$set(a.form,"description",e)},expression:"form.description"}}),a.errors.description?t("div",{staticClass:"text-xs text-red"},[a._v(" "+a._s(a.errors.description[0]))]):a._e()],1),t("section",{staticClass:"q-my-lg q-gutter-y-sm",attrs:{id:"image-section"}},[t("div",[t("q-btn",{staticClass:"mt-2 mr-2",attrs:{label:"Upload Gambar Produk",size:"sm",color:"primary",icon:"upload",type:"button"},on:{click:function(e){return e.preventDefault(),a.selectNewImage(e)}}})],1),t("input",{ref:"image",staticClass:"hidden",attrs:{type:"file",multiple:""},on:{change:a.updateImagePreview}}),a.imagePreview.length?t("q-list",{staticClass:"q-mt-md",attrs:{separator:""}},[t("q-item",{attrs:{dense:""}},[t("q-item-section",[a._v("\n                      Gambar Utama\n                    ")])],1),a._l(a.imagePreview,(function(e,i){return t("q-item",{key:i},[t("q-item-section",[t("img",{staticClass:"bg-white",staticStyle:{width:"100px",height:"70px","object-fit":"contain"},attrs:{src:e}})]),t("q-item-section",{attrs:{side:""}},[t("q-btn",{attrs:{size:"sm",round:"",icon:"delete",glossy:"",color:"red"},on:{click:function(e){return e.preventDefault(),a.removeImage(i)}}})],1)],1)}))],2):a._e()],1)],1),t("div",{attrs:{id:"variants"}},[t("div",{staticClass:"row items-center justify-between q-mt-xl q-pa-md bg-green-1"},[t("div",{staticClass:"text-md2 text-weight-medium"},[a._v("Produk Variasi")]),a.canAddVarian?t("q-btn",{attrs:{label:"Tambah Variasi",color:"accent",size:"12px"},on:{click:function(e){a.varianModal=!0}}}):a._e()],1),a.form.varians.length?t("div",[a.form.varians[0].has_subvarian?t("div",a._l(a.form.varians,(function(e,i){return t("div",{key:i},[t("div",{staticClass:"row items-start justify-between bg-grey-2 q-pa-md q-pt-lg"},[t("div",{staticClass:"text-weight-bold text-md"},[a._v(a._s(a.form.varians[i].label)+" "+a._s(a.form.varians[i].value))]),t("div",{staticClass:"q-gutter-x-sm"},[t("q-btn",{attrs:{unelevated:"",size:"10px",color:"red"},on:{click:function(e){return a.deleteVarian(i)}}},[a._v("Hapus "+a._s(a.form.varians[i].value))]),t("q-btn",{attrs:{unelevated:"",size:"10px",color:"teal"},on:{click:function(e){return a.pushSubVarian(i)}}},[a._v("Tambah Item")])],1)]),t("div",{},[a.form.varians[i].subvarian.length?t("q-list",{staticClass:"bg-white q-pa-sm q-mt-xs"},a._l(a.form.varians[i].subvarian,(function(e,r){return t("q-item",{key:r,staticClass:"q-px-sm"},[t("q-item-section",{attrs:{side:""}},[t("q-btn",{attrs:{round:"",unelevated:"",padding:"2px",icon:"remove",size:"9px",color:"red"},on:{click:function(e){return a.deleteSubvarian(i,r)}}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:a.form.varians[i].subvarian[r].label},model:{value:a.form.varians[i].subvarian[r].value,callback:function(e){a.$set(a.form.varians[i].subvarian[r],"value",e)},expression:"form.varians[varIndex].subvarian[subIndex].value"}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:"Tambahan Harga",mask:"###########"},model:{value:a.form.varians[i].subvarian[r].price,callback:function(e){a.$set(a.form.varians[i].subvarian[r],"price",e)},expression:"form.varians[varIndex].subvarian[subIndex].price"}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:"Stok",mask:"#######"},model:{value:a.form.varians[i].subvarian[r].stock,callback:function(e){a.$set(a.form.varians[i].subvarian[r],"stock",e)},expression:"form.varians[varIndex].subvarian[subIndex].stock"}})],1)],1)})),1):a._e()],1)])})),0):t("div",[t("div",{staticClass:"row items-start justify-between bg-grey-2 q-pa-md q-pt-lg"},[t("div",{staticClass:"text-weight-bold text-md"},[a._v(a._s(a.form.varians[0].label)+" ")]),t("div",{staticClass:"q-gutter-x-sm"},[t("q-btn",{attrs:{unelevated:"",size:"10px",color:"teal"},on:{click:a.pushVarian}},[a._v("Tambah Item")])],1)]),t("q-list",[t("q-list",{staticClass:"bg-white q-pa-sm q-mt-xs"},a._l(a.form.varians,(function(e,i){return t("q-item",{key:i},[t("q-item-section",{attrs:{side:""}},[t("q-btn",{attrs:{round:"",unelevated:"",padding:"2px",icon:"remove",size:"9px",color:"red"},on:{click:function(e){return a.deleteVarian(i)}}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:a.form.varians[i].label},model:{value:a.form.varians[i].value,callback:function(e){a.$set(a.form.varians[i],"value",e)},expression:"form.varians[vIndex].value"}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:"Tambahan Harga",mask:"###########"},model:{value:a.form.varians[i].price,callback:function(e){a.$set(a.form.varians[i],"price",e)},expression:"form.varians[vIndex].price"}})],1),t("q-item-section",[t("q-input",{attrs:{"stack-label":"",filled:"",square:"",required:"",dense:"",label:"Stok",mask:"######"},model:{value:a.form.varians[i].stock,callback:function(e){a.$set(a.form.varians[i],"stock",e)},expression:"form.varians[vIndex].stock"}})],1)],1)})),1)],1)],1)]):a._e()]),t("q-footer",[t("q-btn",{staticClass:"full-width",attrs:{type:"submit",loading:a.loading,label:"Simpan Data"}},[t("q-tooltip",{staticClass:"bg-accent"},[a._v("Simpan Data")])],1)],1)],1),t("q-dialog",{model:{value:a.varianModal,callback:function(e){a.varianModal=e},expression:"varianModal"}},[t("q-card",{staticClass:"card-medium"},[t("div",{staticClass:"card-heading"},[a._v("Tambah varian")]),t("q-form",{on:{submit:function(e){return e.preventDefault(),a.addVarianProduk(e)}}},[t("q-card-section",[t("div",[t("div",{staticClass:"text-md"},[a._v("Varian")]),t("q-input",{attrs:{label:"Label",placeholder:"contoh: Ukuran"},model:{value:a.tempVarian.label,callback:function(e){a.$set(a.tempVarian,"label",e)},expression:"tempVarian.label"}}),t("q-input",{attrs:{label:"Item",placeholder:"contoh: Small, Medium, Large"},model:{value:a.tempVarian.value,callback:function(e){a.$set(a.tempVarian,"value",e)},expression:"tempVarian.value"}}),t("div",{staticClass:"text-grey-7 text-xs q-py-xs"},[a._v("Untuk multiple item, gunakan sparator koma")])],1),a.canToggleSubvarian?t("div",[t("q-checkbox",{attrs:{label:"Subvarian?"},model:{value:a.form.has_subvarian,callback:function(e){a.$set(a.form,"has_subvarian",e)},expression:"form.has_subvarian"}})],1):a._e(),a.mustHaveSubvarian?t("div",{staticClass:"q-mt-md"},[t("div",{staticClass:"text-md"},[a._v("Subvarian")]),t("q-input",{attrs:{label:"Label",placeholder:"contoh: Warna"},model:{value:a.tempSubvarian.label,callback:function(e){a.$set(a.tempSubvarian,"label",e)},expression:"tempSubvarian.label"}}),t("q-input",{attrs:{label:"Item",placeholder:"contoh: Merah, Biru, Ungu"},model:{value:a.tempSubvarian.value,callback:function(e){a.$set(a.tempSubvarian,"value",e)},expression:"tempSubvarian.value"}}),t("div",{staticClass:"text-grey-7 text-xs q-py-xs"},[a._v("Untuk multiple item, gunakan sparator koma")])],1):a._e(),t("div",{staticClass:"flex justify-end q-mt-md q-gutter-sm"},[t("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Tutup",flat:"",color:"primary"}}),t("q-btn",{attrs:{unelevated:"",label:"Tambah",type:"submit",color:"primary"}})],1)])],1)],1)],1),t("q-dialog",{model:{value:a.categoryModal,callback:function(e){a.categoryModal=e},expression:"categoryModal"}},[t("q-card",{staticStyle:{"max-width":"450px",width:"90%"}},[t("div",{staticStyle:{height:"350px","overflow-y":"auto"}},[t("div",[t("div",{staticClass:"bg-white relative"},[t("category-block",{ref:"category",attrs:{selectable:"",categories:a.categories.data},on:{onClear:a.clearCategory,onClose:function(e){a.categoryModal=!1},onSelect:a.selectCategory}})],1)])]),t("q-card-actions",{staticClass:"justify-end"},[t("q-btn",{attrs:{flat:"",label:"Batal",color:"dark"},on:{click:function(e){a.categoryModal=!1}}})],1)],1)],1)],1)},r=[],s=t("ded3"),n=t.n(s),o=(t("a434"),t("ac1f"),t("1276"),t("159b"),t("a4d3"),t("e01a"),t("d3b7"),t("e6cf"),t("a79d"),t("4de4"),t("2f62")),l=t("391e"),c={components:{CategoryBlock:l["a"]},name:"ProductFormCreate",data:function(){return{varianModal:!1,tempVarian:{label:"",value:""},tempSubvarian:{label:"",value:"",stock:"",price:"",items:"",sku:""},requiredRules:[function(a){return a&&a.length>0||"Field harus diisi."}],form:{title:"",price:"",weight:"",stock:"",description:"",category_id:"",varians:[],images:[],sku:"",has_subvarian:!1},imagePreview:[],variantModalForm:!1,categoryModal:!1,categorySelected:null}},computed:{user:function(){return this.$store.state.user.user},errors:function(){return this.$store.state.errors},loading:function(){return this.$store.state.loading},categories:function(){return this.$store.state.category.categoryAll},canToggleSubvarian:function(){return!this.form.varians.length||!this.form.varians[0].has_subvarian},mustHaveSubvarian:function(){return!(!this.form.varians.length||!this.form.varians[0].has_varian)||!!this.form.has_subvarian},canAddVarian:function(){return!(this.form.varians.length&&!this.form.varians[0].has_subvarian)}},methods:n()(n()(n()(n()({},Object(o["b"])("product",["productStore"])),Object(o["b"])("category",["getCategories","getAllCategories"])),Object(o["b"])("customerService",["getCustomerServices"])),{},{selectCategory:function(a){this.categorySelected=a,this.form.category_id=a.id,this.categoryModal=!1},clearCategory:function(){this.form.category_id="",this.categorySelected=null},onUpdateImage:function(a){this.form.product_images.push(a)},onDeleteImage:function(a){this.form.product_images.splice(a,1)},onSubmitForm:function(a){this.form.variants=a,this.variantModalForm=!1},deleteVarian:function(a){var e=this;this.$q.dialog({title:"Yakin akan menghapus data?",cancel:!0}).onOk((function(){e.form.varians.splice(a,1)}))},deleteSubvarian:function(a,e){this.form.varians[a].subvarian.splice(e,1),this.form.varians[a].subvarian.length||this.form.varians.splice(a,1)},pushSubVarian:function(a){var e=this.form.varians[a],t={label:e.subvarian[0].label,value:"",stock:0,sku:this.generateSku(8),price:0};this.form.varians[a].subvarian.push(t)},pushVarian:function(){this.form.varians.push({has_subvarian:!1,label:this.form.varians[0].label,value:"",stock:0,sku:this.generateSku(8),price:0})},handleAddVarian:function(){this.varianModal=!0},addVarianProduk:function(){var a=this,e=this.tempVarian.value.split(",");this.form.has_subvarian?e.forEach((function(e){var t=null;t={has_subvarian:!0,label:a.tempVarian.label,value:e,subvarian:[]};var i=a.tempSubvarian.value.split(",");i.forEach((function(e){var i={label:a.tempSubvarian.label,value:e,stock:0,sku:a.generateSku(13),price:0};t.subvarian.push(i)})),a.form.varians.push(t)})):e.forEach((function(e){var t=null;t={has_subvarian:!1,label:a.tempVarian.label,value:e,stock:0,sku:a.generateSku(12),price:0},a.form.varians.push(t)})),this.varianModal=!1},submit:function(){var a=this;this.$store.commit("SET_LOADING",!0);var e=new FormData;e.append("title",this.form.title),e.append("price",this.form.price),e.append("weight",this.form.weight),e.append("sku",this.generateSku()),e.append("has_subvarian",this.form.has_subvarian),e.append("stock",this.form.stock),e.append("description",this.form.description),this.form.category_id&&e.append("category_id",this.form.category_id),this.form.varians.length>0&&e.append("varians",JSON.stringify(this.form.varians));for(var t=0;t<this.form.images.length;t++){var i=this.form.images[t];e.append("images["+t+"]",i)}this.productStore(e).then((function(){"seller"==a.user.role?(a.$store.dispatch("seller/getProducts"),a.$router.push({name:"SellerProduct"})):(a.$store.dispatch("product/getAdminProducts"),a.$router.push({name:"AdminProductIndex"}))})).finally((function(){return a.$store.commit("SET_LOADING",!1)}))},selectNewImage:function(){this.$refs.image.click()},updateImagePreview:function(){for(var a=this,e=this.$refs.image.files,t=0;t<e.length;t++){this.form.images.push(e[t]);var i=new FileReader;i.onload=function(e){a.imagePreview.push(e.target.result)},i.readAsDataURL(e[t])}},removeImage:function(a){this.imagePreview=this.imagePreview.filter((function(e,t){return t!==a})),this.form.images=this.form.images.filter((function(e,t){return t!==a}))}}),mounted:function(){this.getAllCategories()}},u=c,m=t("2877"),d=t("9989"),v=t("e359"),f=t("65c6"),p=t("9c40"),b=t("6ac5"),g=t("0378"),h=t("27f9"),k=t("d66b"),q=t("1c1c"),x=t("66e5"),y=t("4074"),_=t("7ff0"),C=t("05c0"),S=t("24e8"),w=t("f09f"),$=t("a370"),I=t("8f8e"),V=t("4b7e"),P=t("2eeb"),M=t("7f67"),Q=t("eebe"),T=t.n(Q),D=Object(m["a"])(u,i,r,!1,null,null,null);e["default"]=D.exports;T()(D,"components",{QPage:d["a"],QHeader:v["a"],QToolbar:f["a"],QBtn:p["a"],QToolbarTitle:b["a"],QForm:g["a"],QInput:h["a"],QEditor:k["a"],QList:q["a"],QItem:x["a"],QItemSection:y["a"],QFooter:_["a"],QTooltip:C["a"],QDialog:S["a"],QCard:w["a"],QCardSection:$["a"],QCheckbox:I["a"],QCardActions:V["a"]}),T()(D,"directives",{GoBack:P["a"],ClosePopup:M["a"]})}}]);