(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[32],{de0f:function(e,t,i){"use strict";i.r(t);var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("q-page",{},[i("q-header",[i("q-toolbar",[i("q-btn",{directives:[{name:"go-back",rawName:"v-go-back.single",modifiers:{single:!0}}],attrs:{flat:"",round:"",dense:"",icon:"arrow_back"}}),i("q-toolbar-title",[e._v("\n       "+e._s(e.formType)+" Kategori\n      ")])],1)],1),i("div",{staticClass:"q-pa-md q-gutter-y-md"},[i("div",{staticClass:"cursor-pointer q-pa-md full-width border q-filled  text-grey-8",attrs:{label:"Parent Kategori"},on:{click:function(t){e.parentModal=!0}}},[e._v(e._s(e.form.category_id?e.parentCategoryTitle:"Pilih Parent Kategori"))]),i("q-input",{attrs:{filled:"",label:"Title",rules:[function(e){return e&&e.length>0||"Wajib diisi"}]},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}}),i("q-input",{attrs:{filled:"",autogrow:"",label:"Deskripsi"},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}}),i("q-input",{attrs:{filled:"",mask:"####",label:"Urutan",rules:[function(e){return e&&e.length>0||"Wajib diisi"}]},model:{value:e.form.weight,callback:function(t){e.$set(e.form,"weight",t)},expression:"form.weight"}}),e.form.category_id?e._e():[i("div",{staticClass:"border rounded q-pa-sm"},[i("div",[i("q-toggle",{attrs:{label:"Tampilkan produk di beranda"},model:{value:e.form.is_front,callback:function(t){e.$set(e.form,"is_front",t)},expression:"form.is_front"}}),i("div",{staticClass:"text-xs text-grey-6 q-pa-xs"},[e._v("\n          Pilihan apakah produk dibawah kategori ini akan di listing di halaman beranda\n        ")])],1)]),i("div",[i("div",{staticClass:"row items-center justify-between"},[i("q-btn",{staticClass:"mt-2 mr-2",attrs:{label:"Upload Ikon",size:"sm",color:"primary",icon:"upload",type:"button"},on:{click:function(t){return t.preventDefault(),e.selectNewImage(t)}}}),i("q-btn",{staticClass:"mt-2 mr-2",attrs:{label:"Upload Banner",size:"sm",color:"teal",icon:"upload",type:"button"},on:{click:function(t){return t.preventDefault(),e.selectNewBanner(t)}}})],1),e.errors.images?i("div",{staticClass:"text-xs text-red q-my-md"},[e._v(" "+e._s(e.errors.images[0]))]):e._e()]),i("div",[i("input",{ref:"image",staticClass:"hidden",attrs:{type:"file"},on:{change:e.updateImagePreview}}),i("input",{ref:"banner",staticClass:"hidden",attrs:{type:"file"},on:{change:e.updateBannerPreview}}),e.imagePreview?i("div",{staticClass:"my-lg"},[i("div",{staticClass:"text-weight-bold text-md"},[e._v("Ikon")]),i("q-list",{attrs:{bordered:""}},[i("q-item",[i("q-item-section",[i("img",{staticClass:"shadow-4 q-pa-xs bg-white",staticStyle:{width:"80px",height:"auto","object-fit":"contain"},attrs:{src:e.imagePreview}})]),i("q-space"),i("q-item-section",{attrs:{side:""}},[i("q-btn",{attrs:{size:"sm",color:"red",glossy:"",round:"",icon:"delete"},on:{click:e.removeImage}})],1)],1)],1)],1):e._e()]),e.bannerPreview?i("div",{staticClass:"my-lg"},[i("div",{staticClass:"text-weight-bold text-md"},[e._v("Banner")]),i("q-list",{attrs:{bordered:""}},[i("q-item",[i("q-item-section",[i("img",{staticClass:"shadow-4 q-pa-xs bg-white",staticStyle:{width:"200px",height:"auto","object-fit":"contain"},attrs:{src:e.bannerPreview}})]),i("q-space"),i("q-item-section",{attrs:{side:""}},[i("q-btn",{attrs:{size:"sm",color:"red",glossy:"",round:"",icon:"delete"},on:{click:e.removeBanner}})],1)],1)],1)],1):e._e()]],2),i("q-dialog",{model:{value:e.parentModal,callback:function(t){e.parentModal=t},expression:"parentModal"}},[i("q-card",{staticClass:"card-medium"},[i("div",{staticClass:"card-heading"},[e._v("Kategori")]),i("div",{staticClass:"bg-white relative"},[i("div",{staticClass:"q-pa-sm"},[i("category-block",{ref:"category",attrs:{selectable:"",categories:e.categoryAll},on:{onClose:e.onCloseSelectableCategory,onSelect:e.onSelectCategory}})],1)])])],1),i("q-footer",{staticClass:"q-px-md q-py-sm bg-white"},[i("q-btn",{staticClass:"full-width",attrs:{loading:e.loading,label:"Simpan Data",color:"primary"},on:{click:e.submit}},[i("q-tooltip",{staticClass:"bg-accent"},[e._v("Simpan Data")])],1)],1)],1)},r=[],s=i("ded3"),o=i.n(s),n=(i("a4d3"),i("e01a"),i("2f62")),c=i("758b"),l=i("391e"),d={components:{CategoryBlock:l["a"]},data:function(){return{parentModal:!1,category:this.$route.query.edit?this.$store.getters["category/getById"](parseInt(this.$route.query.edit)):"",formType:"Add",form:{id:"",images:"",title:"",description:"",category_id:"",is_front:!0,weight:1,banner:"",remove_banner:!1},modal:!1,imagePreview:"",bannerPreview:"",categoryAll:[],categorySelected:null,isOpenSelect:!1}},computed:o()(o()({},Object(n["e"])({loading:function(e){return e.loading},errors:function(e){return e.errors},categories:function(e){return e.category.categories}})),{},{parentCategoryTitle:function(){return this.categorySelected?this.categorySelected.title:""}}),created:function(){var e=this;this.categories.data.length||this.getCategories(),this.getCategryAll(),this.clearForm(),this.$route.query.edit?(this.formType="Edit",this.category?this.setData():this.getCategory(this.$route.query.edit).then((function(t){e.category=t.data.results,e.setData()}))):(this.formType="Add",this.category={},this.clearForm())},methods:o()(o()({},Object(n["b"])("category",["categoryStore","getCategories","getCategory","categoryUpdate"])),{},{onCloseSelectableCategory:function(){this.isOpenSelect=!1},clearCategorySelected:function(){this.isOpenSelect=!1,this.categorySelected=null,this.form.category_id=""},onSelectCategory:function(e){this.form.category_id=e.id,this.categorySelected=e,this.isOpenSelect=!1},getCategryAll:function(){var e=this;Object(c["a"])().get("category-all").then((function(t){200==t.status&&(e.categoryAll=t.data.results)}))},submit:function(){var e=new FormData;e.append("title",this.form.title),e.append("description",this.form.description),this.form.category_id||(e.append("images",this.form.images),e.append("banner",this.form.banner)),e.append("weight",this.form.weight),e.append("is_front",this.form.is_front),e.append("category_id",this.form.category_id),"Edit"==this.formType&&e.append("remove_banner",this.form.remove_banner),"Add"==this.formType&&(e.append("_method","POST"),this.categoryStore(e)),"Edit"==this.formType&&(e.append("_method","PUT"),this.categoryUpdate({id:this.form.id,data:e}))},selectNewImage:function(){this.$refs.image.click()},selectNewBanner:function(){this.$refs.banner.click()},updateImagePreview:function(){var e=this,t=this.$refs.image.files[0];if(t){this.form.images=t;var i=new FileReader;i.onload=function(t){e.imagePreview=t.target.result},i.readAsDataURL(t)}},updateBannerPreview:function(){var e=this,t=this.$refs.banner.files[0];if(t){this.form.banner=t;var i=new FileReader;i.onload=function(t){e.bannerPreview=t.target.result},i.readAsDataURL(t)}},setData:function(){this.form.id=this.category.id,this.form.title=this.category.title,this.form.description=this.category.description?this.category.description:"",this.form.weight=this.category.weight,this.form.is_front=this.category.is_front,this.form.is_special=this.category.is_special,this.imagePreview=this.category.src?this.category.src:"",this.bannerPreview=this.category.banner_src?this.category.banner_src:""},clearForm:function(){this.form.id="",this.form.title="",this.form.description="",this.form.weight=1,this.form.is_front=!0,this.form.is_special="",this.form.images="",this.form.banner="",this.form.remove_banner=!1,this.imagePreview="",this.bannerPreview=""},removeImage:function(){this.imagePreview="",this.form.images=""},removeBanner:function(){this.bannerPreview="",this.form.banner="",this.form.remove_banner=!0}})},g=d,m=i("2877"),f=i("9989"),h=i("e359"),p=i("65c6"),u=i("9c40"),b=i("6ac5"),y=i("27f9"),v=i("9564"),w=i("1c1c"),_=i("66e5"),q=i("4074"),C=i("2c91"),k=i("24e8"),P=i("f09f"),S=i("7ff0"),x=i("05c0"),T=i("2eeb"),Q=i("eebe"),$=i.n(Q),I=Object(m["a"])(g,a,r,!1,null,null,null);t["default"]=I.exports;$()(I,"components",{QPage:f["a"],QHeader:h["a"],QToolbar:p["a"],QBtn:u["a"],QToolbarTitle:b["a"],QInput:y["a"],QToggle:v["a"],QList:w["a"],QItem:_["a"],QItemSection:q["a"],QSpace:C["a"],QDialog:k["a"],QCard:P["a"],QFooter:S["a"],QTooltip:x["a"]}),$()(I,"directives",{GoBack:T["a"]})}}]);