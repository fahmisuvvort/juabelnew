(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[56],{"0e46":function(e,t,a){"use strict";a.r(t);var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",[a("q-header",[a("q-toolbar",[a("q-toolbar-title",[e._v("\n        Dashboard\n      ")]),a("q-btn",{attrs:{to:{name:"Home",query:{load:"true"}},flat:"","icon-right":"arrow_forward",label:"Lihat Toko","no-caps":""}})],1)],1),a("div",{staticClass:"overflow-x-hidden relative"},[a("q-list",{attrs:{separator:""}},[e._l(e.menus,(function(t){return a("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:t.label,attrs:{clickable:"",to:{name:t.path}}},[a("q-item-section",{attrs:{avatar:""}},[a("q-avatar",{attrs:{color:t.color,"text-color":"white",icon:t.icon,size:"md"}})],1),a("q-item-section",[a("q-item-label",[e._v(e._s(t.label))]),a("q-item-label",{attrs:{caption:"",lines:"1"}},[e._v(e._s(t.caption))])],1),a("q-item-section",{attrs:{side:""}},[a("q-icon",{attrs:{name:"navigate_next"}})],1)],1)})),a("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],attrs:{clickable:""},on:{click:function(t){return t.preventDefault(),e.logout(t)}}},[a("q-item-section",{attrs:{avatar:""}},[a("q-avatar",{attrs:{color:"grey","text-color":"white",icon:"logout",size:"md"}})],1),a("q-item-section",[a("q-item-label",[e._v("Logout")]),a("q-item-label",{attrs:{caption:"",lines:"1"}},[e._v("Keluar Sesi")])],1),a("q-item-section",{attrs:{side:""}},[a("q-icon",{attrs:{name:"navigate_next"}})],1)],1)],2)],1)],1)},n=[],r={data:function(){return{isShowen:!1,menus:[{label:"Akun",caption:"Pengaturan Akun",path:"SellerAccount",icon:"person",color:"green"},{label:"Produk",caption:"Kelola pesanan",path:"SellerProduct",icon:"inventory_2",color:"accent"},{label:"Order",caption:"Kelola pesanan",path:"SellerOrder",icon:"receipt",color:"blue"},{label:"Gudang Pengiriman",caption:"Pengaturan toko dan gudang pengiriman",path:"SellerWarehouse",icon:"store",color:"deep-orange"}]}},methods:{logout:function(){this.$store.dispatch("user/logout")},exitApp:function(){this.$store.dispatch("user/exitApp",navigator)}}},i=r,l=a("2877"),c=a("9989"),s=a("e359"),p=a("65c6"),u=a("6ac5"),m=a("9c40"),d=a("1c1c"),b=a("66e5"),v=a("4074"),q=a("cb32"),g=a("0170"),h=a("0016"),f=a("714f"),w=a("eebe"),_=a.n(w),k=Object(l["a"])(i,o,n,!1,null,null,null);t["default"]=k.exports;_()(k,"components",{QPage:c["a"],QHeader:s["a"],QToolbar:p["a"],QToolbarTitle:u["a"],QBtn:m["a"],QList:d["a"],QItem:b["a"],QItemSection:v["a"],QAvatar:q["a"],QItemLabel:g["a"],QIcon:h["a"]}),_()(k,"directives",{Ripple:f["a"]})}}]);