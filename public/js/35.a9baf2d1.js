(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[35],{"841d":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{},[a("q-header",{staticClass:"text-primary bg-white box-shadow",attrs:{reveal:"","reveal-offset":10}},[a("q-toolbar",[a("q-btn",{attrs:{flat:"",round:"",dense:"",icon:"arrow_back"},on:{click:t.handleBackButton}}),a("q-toolbar-title",{staticClass:"text-weight-bold brand"},[t._v(t._s(t.title))])],1)],1),t.carts&&t.carts.items.length?a("div",{ref:"top",staticClass:"q-pb-xl",attrs:{id:"checkout"}},[a("q-stepper",{attrs:{"keep-alive":"",flat:"",color:"primary","alternative-labels":"",animated:""},model:{value:t.step,callback:function(e){t.step=e},expression:"step"}},[a("q-step",{attrs:{name:1,title:"Pengiriman",done:t.step>1,icon:"local_shipping"}},[a("shipping-address",{attrs:{canEmail:"",isModalAddress:t.isAvailableOldAddress},on:{closeModal:function(e){t.isAvailableOldAddress=!1},checkStep:t.checkStepOk}})],1),"COD"!=t.formOrder.shipping_courier_name?a("q-step",{attrs:{name:2,title:"Pembayaran",done:t.step>2,icon:"payments"}},[a("payment-list",{ref:"paymentList",attrs:{paymentSelected:t.paymentSelected,payments:t.paymentChanels},on:{checkStep:t.checkStepOk,onSelectPayment:t.onSelectPayment}})],1):t._e(),a("q-step",{attrs:{name:3,title:"Review",done:t.step>3,icon:"playlist_add_check"}},[a("review-order",{attrs:{paymentSelected:t.paymentSelected,noPayment:t.isCantPaymentStep}})],1)],1)],1):t._e(),a("q-inner-loading",{attrs:{showing:t.loading}},[a("q-spinner-facebook",{attrs:{size:"50px",color:"primary"}})],1),a("div",{staticClass:"bg-white q-pa-md q-gutter-y-sm column",class:{"sticky-bottom":t.$q.platform.is.desktop}},[3!=t.step&&"COD"!=t.formOrder.shipping_courier_name?a("q-btn",{attrs:{disabled:!t.isOk,"no-caps":"",unelevated:"",label:"Langkah Selanjutnya",color:"primary"},on:{click:t.next}}):t._e(),"COD"==t.formOrder.shipping_courier_name&&3!=t.step?a("q-btn",{attrs:{disabled:!t.isOk,"no-caps":"",unelevated:"",label:"Langkah Selanjutnya",color:"primary"},on:{click:function(e){t.step=3}}}):t._e(),3==t.step?a("q-btn",{attrs:{disabled:t.loading,"no-caps":"",unelevated:"",label:"Proses Pesanan",color:"primary"},on:{click:t.submitOrder}}):t._e()],1)],1)},n=[],r=a("ded3"),i=a.n(r),o=(a("ac1f"),a("1276"),a("a15b"),a("159b"),a("99af"),a("b0c0"),a("5319"),a("2ca0"),a("758b")),c=a("2f62"),m=a("54df"),l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"q-gutter-y-lg"},[t.payments.localbanks.length?a("div",[a("fieldset",[a("legend",{staticClass:"q-px-sm"},[t._v("Bank Transfer")]),a("div",{staticClass:"row q-gutter-sm payment-container"},t._l(t.payments.localbanks,(function(e,s){return a("div",{key:s,staticClass:"box-shadow cursor-pointer payment-list bank_trf",class:{"is-selected":t.isSelectedBank(e.id)},on:{click:function(a){return t.selectPaymentBank(e)}}},[a("div",[a("div",{staticClass:"text-center text-weight-bold text-md"},[t._v(t._s(e.bank_name))]),a("div",{staticClass:"text-weight-medium"},[t._v("Bank Transfer")])])])})),0),a("div",{staticClass:"q-pa-sm text-caption"},[t._v("*Verifikasi manual")])])]):t._e(),t.virtualAccounts.length?a("div",[a("fieldset",[a("legend",{staticClass:"q-px-sm"},[t._v("Virtual Account")]),a("div",{staticClass:"row q-gutter-sm payment-container"},t._l(t.virtualAccounts,(function(e,s){return a("div",{key:s},[a("div",{staticClass:"box-shadow cursor-pointer payment-list",class:{"is-selected":t.isSelected(e.code)},on:{click:function(a){return t.selectPayment(e)}}},[a("div",{staticClass:"image"},[e.icon_url?a("img",{attrs:{src:e.icon_url}}):a("img",{attrs:{src:"/static/no-image.png"}})]),a("div",{staticClass:"text-center name"},[t._v(t._s(e.name))])]),t.isFeeCustomer(e.fee_customer)?a("div",{staticClass:"text-grey-7 text-xs q-pa-xs text-center"},[t._v("Fee "+t._s(t.moneyIDR(t.calculateFee(e.fee_customer))))]):t._e()])})),0)])]):t._e(),t.convenienceStore.length?a("div",[a("fieldset",[a("legend",{staticClass:"q-px-sm"},[t._v("Convenion Store")]),a("div",{staticClass:"row q-gutter-sm payment-container"},t._l(t.convenienceStore,(function(e,s){return a("div",{key:s},[a("div",{staticClass:"box-shadow cursor-pointer payment-list",class:{"is-selected":t.isSelected(e.code)},on:{click:function(a){return t.selectPayment(e)}}},[a("div",{staticClass:"image"},[e.icon_url?a("img",{attrs:{src:e.icon_url}}):a("img",{attrs:{src:"/static/no-image.png"}})]),a("div",{staticClass:"text-center name"},[t._v(t._s(e.name))])]),t.isFeeCustomer(e.fee_customer)?a("div",{staticClass:"text-grey-7 text-xs q-pa-xs text-center"},[t._v("Fee "+t._s(t.moneyIDR(t.calculateFee(e.fee_customer))))]):t._e()])})),0)])]):t._e(),t.ewalet.length?a("div",[a("fieldset",[a("legend",{staticClass:"q-px-sm"},[t._v("E-Walet")]),a("div",{staticClass:"row q-gutter-sm payment-container"},t._l(t.ewalet,(function(e,s){return a("div",{key:s},[a("div",{staticClass:"box-shadow cursor-pointer payment-list",class:{"is-selected":t.isSelected(e.code)},on:{click:function(a){return t.selectPayment(e)}}},[a("div",{staticClass:"image"},[e.icon_url?a("img",{attrs:{src:e.icon_url}}):a("img",{attrs:{src:"/static/no-image.png"}})]),a("div",{staticClass:"text-center name"},[t._v(t._s(e.name))])]),t.isFeeCustomer(e.fee_customer)?a("div",{staticClass:"text-grey-7 text-xs q-pa-xs text-center"},[t._v("Fee "+t._s(t.moneyIDR(t.calculateFee(e.fee_customer))))]):t._e()])})),0)])]):t._e()])},d=[],p=(a("4de4"),a("0481"),{props:{payments:Object,isCod:Boolean,paymentSelected:Object},computed:{virtualAccounts:function(){return this.payments&&this.payments.paymentGateway.length?this.payments.paymentGateway.filter((function(t){return"Virtual Account"==t.group&&1==t.active})):[]},convenienceStore:function(){return this.payments&&this.payments.paymentGateway.length?this.payments.paymentGateway.filter((function(t){return"Convenience Store"==t.group&&1==t.active})):[]},ewalet:function(){return this.payments&&this.payments.paymentGateway.length?this.payments.paymentGateway.filter((function(t){return"E-Wallet"==t.group&&1==t.active})):[]},isSelectedCod:function(){return!!this.paymentSelected&&"COD"==this.paymentSelected.payment_type},formOrder:function(){return this.$store.getters["order/getFormOrder"]}},methods:{isFeeCustomer:function(t){return t.flat>0||t.percent>0},calculateFee:function(t){var e=parseInt(t.flat);if(t.percent>0){var a=parseInt(this.formOrder.total)*parseFloat(t.percent)/100;t.flat>0?e+=a:e=a}return parseInt(Math.ceil(e))},isSelected:function(t){return!!this.paymentSelected&&this.paymentSelected.code==t},isSelectedBank:function(t){return!!this.paymentSelected&&this.paymentSelected.id==t},selectCodPayment:function(){var t={payment_type:"COD",payment_method:"COD",payment_name:"Bayar Ditempat"};this.commitFormOrder(t)},selectPayment:function(t){this.commitFormOrder(i()(i()({},t),{},{payment_type:"PAYMENT_GATEWAY",payment_fee:this.calculateFee(t.fee_customer)}))},selectPaymentBank:function(t){this.commitFormOrder(i()(i()({},t),{},{payment_type:"BANK_TRANSFER"}))},commitFormOrder:function(t){this.$emit("onSelectPayment",t);var e={payment_method:"",payment_name:"",payment_code:"",payment_type:"",payment_fee:0};for(var a in t.payment_fee&&(e.payment_fee=parseInt(t.payment_fee)),"BANK_TRANSFER"==t.payment_type&&(e.payment_method="BANK_TRANSFER",e.payment_name="".concat(t.bank_name," ").concat(t.bank_office," - a/n ").concat(t.account_name),e.payment_code=t.account_number,e.payment_type=t.payment_type),"PAYMENT_GATEWAY"==t.payment_type&&(e.payment_method=t.code,e.payment_name=t.name,e.payment_code="",e.payment_type=t.payment_type),"COD"==t.payment_type&&(e.payment_method=t.payment_method,e.payment_name=t.payment_name,e.payment_code="COD",e.payment_type="COD"),e)this.$store.commit("order/SET_FORM_ORDER",{key:a,value:e[a]});this.$emit("checkStep")}}}),u=p,_=a("2877"),h=Object(_["a"])(u,l,d,!1,null,null,null),y=h.exports,f=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",["OVO"==t.formOrder.payment_method?a("div",{staticClass:"q-mb-md"},[t._m(0)]):t._e(),a("fieldset",[a("legend",{staticClass:"q-pa-sm"},[t._v("Info Pengiriman")]),a("table",{staticClass:"table dense"},[a("tr",[a("th",{attrs:{align:"left"}},[t._v("Penerima")]),a("td",[t._v(":")]),a("td",[t._v(t._s(t.formOrder.customer_name))])]),a("tr",[a("th",{attrs:{align:"left"}},[t._v("Whatsapp")]),a("td",[t._v(":")]),a("td",[t._v(t._s(t.formOrder.customer_phone))])]),a("tr",[a("th",{attrs:{align:"left"}},[t._v("Alamat")]),a("td",[t._v(":")]),a("td",[a("div",{domProps:{innerHTML:t._s(t.formOrder.address)}})])]),a("tr",[a("th",{attrs:{align:"left"}},[t._v("Kurir")]),a("td",[t._v(":")]),a("td",[t._v(t._s(t.formOrder.shipping_courier_name))])]),t.formOrder.shipping_courier_service?a("tr",[a("th",{attrs:{align:"left"}},[t._v("Servis")]),a("td",[t._v(":")]),a("td",[t._v(t._s(t.formOrder.shipping_courier_service))])]):t._e()])]),a("fieldset",{staticClass:"q-mt-lg"},[a("legend",{staticClass:"q-pa-sm"},[t._v("Ringkasan Order")]),t.formOrder.items.length?a("div",{staticClass:"bg-grey-2 q-mb-md"},[a("q-list",{attrs:{separator:""}},t._l(t.formOrder.items,(function(e){return a("q-item",{key:e.id},[a("q-item-section",{attrs:{side:"",top:""}},[a("q-img",{staticStyle:{width:"50px",height:"50px"},attrs:{src:e.image_url}})],1),a("q-item-section",[a("div",{staticClass:"col"},[a("div",{staticClass:"text-weight-medium"},[t._v(t._s(e.name))]),a("div",{staticClass:"text-caption text-grey-7"},[t._v(t._s(e.note))]),a("div",{staticClass:"text-grey-7"},[t._v(t._s(e.quantity+"x "+t.moneyIDR(e.price)))])])])],1)})),1)],1):t._e(),a("div",{staticClass:"flex justify-end"},[a("table",{staticClass:"table dense"},[a("tr",[a("td",{attrs:{align:"right"}},[t._v("Subtotal")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("td",{attrs:{align:"right"}},[t._v(t._s(t.moneyIDR(t.formOrder.subtotal)))])]),t.formOrder.shipping_cost?a("tr",[a("td",{attrs:{align:"right"}},[t._v("Ongkos Kirim (+)")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("td",{attrs:{align:"right"}},[t._v(t._s(t.moneyIDR(t.formOrder.shipping_cost)))])]):t._e(),t.formOrder.coupon_discount?a("tr",[a("td",{attrs:{align:"right"}},[t._v("Diskon (-)")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("td",{attrs:{align:"right"}},[t._v(t._s(t.formOrder.coupon_discount?t.moneyIDR(t.formOrder.coupon_discount):0))])]):t._e(),a("tr",{staticStyle:{"border-bottom":"1px solid"}},[a("td",{attrs:{align:"right"}},[t._v("Jumlah")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("td",{attrs:{align:"right"}},[t._v(t._s(t.moneyIDR(t.formOrder.total)))])])])]),t.formOrder.payment_fee?a("div",{staticClass:"flex justify-end q-mt-sm bg-grey-1 q-py-sm"},[a("table",{staticClass:"table dense"},[t.formOrder.payment_fee?a("tr",[a("td",{staticClass:"text-xs",attrs:{align:"right"}},[t._v("Payment Fee [ "+t._s(t.formOrder.payment_name)+" ]")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("td",{attrs:{align:"right"}},[t._v(t._s(t.moneyIDR(t.formOrder.payment_fee)))])]):t._e(),a("tr",[a("th",{attrs:{align:"right"}},[t._v("Total Tagihan")]),a("td",{attrs:{align:"right"}},[t._v(":")]),a("th",{attrs:{align:"right"}},[t._v(t._s(t.moneyIDR(t.formOrder.total+t.formOrder.payment_fee)))])])])]):t._e()]),t.formOrder.payment_method&&t.paymentSelected?a("fieldset",{staticClass:"q-mt-lg"},[a("legend",{staticClass:"q-pa-sm"},[t._v("Pembayaran")]),a("div",{staticClass:"row q-gutter-sm"},["COD"==t.formOrder.payment_method?[t._m(1)]:[a("div",{staticClass:"box-shadow payment-list is-selected"},[t.paymentSelected.icon_url?a("div",{staticClass:"image"},[a("img",{attrs:{src:t.paymentSelected.icon_url}})]):t._e(),"BANK_TRANSFER"==t.paymentSelected.payment_type?a("div",{staticClass:"flex justify-center items-center",staticStyle:{margin:"auto"}},[a("div",{staticClass:" text-weight-bold text-md"},[t._v(t._s(t.paymentSelected.bank_name))]),a("div",[t._v("Bank Transfer")])]):a("div",{staticClass:"text-center name"},[t._v("\n              "+t._s(t.paymentSelected.name)+"\n            ")])])]],2)]):t._e()])},g=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"bg-yellow-2 text-grey-7 q-pa-sm"},[a("div",{staticClass:"text-weight-bold"},[t._v("Note:")]),a("div",[t._v("\n        Anda memilih metode pembayaran OVO, "),a("br"),t._v("Pastikan nomor ponsel dan email yang anda inputkan sama dengan yang terdaftar di OVO milik anda.\n      ")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"box-shadow payment-list is-selected"},[a("div",{staticClass:"text-weight-bold text-h5 text-center"},[t._v("COD")]),a("div",{staticClass:"text-center name q-pa-xs"},[t._v("Bayar Ditempat")])])}],v={name:"ReviewOrder",props:{carts:Object,form:Object,paymentSelected:Object,noPayment:Boolean},computed:{formOrder:function(){return this.$store.getters["order/getFormOrder"]}},methods:{money:function(t){return new Intl.NumberFormat("id-ID",{style:"currency",currency:"IDR"}).format(t)}}},O=v,C=a("1c1c"),b=a("66e5"),k=a("4074"),x=a("068f"),S=a("eebe"),w=a.n(S),q=Object(_["a"])(O,f,g,!1,null,null,null),D=q.exports;w()(q,"components",{QList:C["a"],QItem:b["a"],QItemSection:k["a"],QImg:x["a"]});var P={components:{ShippingAddress:m["a"],PaymentList:y,ReviewOrder:D},name:"CheckoutPage",data:function(){return{isAvailableOldAddress:!1,isOk:!1,formLoading:!1,paymentChanels:{localbanks:[],paymentGateway:[]},step:1,paymentSelected:null}},watch:{step:function(){this.checkStepOk(),this.toTop()}},computed:{formOrder:function(){return this.$store.getters["order/getFormOrder"]},carts:function(){return this.$store.getters["cart/getCarts"]},shop:function(){return this.$store.state.shop},config:function(){return this.$store.state.config},isCod:function(){return"COD"==this.formOrder.shipping_courier_name},loading:function(){return this.$store.state.loading},isCantPaymentStep:function(){return"COD"==this.formOrder.shipping_courier_name||!this.config.is_payment_gateway},title:function(){return 1==this.step?"Pengiriman":2==this.step?"Pembayaran":3==this.step?"Review Order":"Checkout"},session_id:function(){return this.$store.state.session_id}},mounted:function(){this.carts.items.length||this.$router.push({name:"Cart"}),this.config&&this.config.is_payment_gateway&&!this.paymentChanels.paymentGateway.length&&this.getPaymentChanel(),this.paymentChanels.localbanks.length||this.getLocalBanks(),this.checkDataUser(),this.collectOrder()},methods:i()(i()({},Object(c["b"])("order",["storeOrder"])),{},{collectOrder:function(){this.$store.commit("order/COLLECT_ORDER",this.carts)},checkDataUser:function(){localStorage.getItem("user_data")&&(this.isAvailableOldAddress=!0)},handleBackButton:function(){this.step>1?this.isCod?this.step=1:this.step-=1:this.$router.push({name:"Cart"})},toTop:function(){var t=this;setTimeout((function(){var e=t.$refs.top;e.scrollIntoView({behavior:"smooth"})}),500)},checkStepOk:function(){1==this.step?this.formOrder.customer_name&&this.formOrder.customer_email&&this.formOrder.customer_phone&&this.formOrder.shipping_courier_name&&this.formOrder.address&&(this.isCod||this.formOrder.shipping_cost)?this.isOk=!0:this.isOk=!1:2==this.step?this.formOrder.payment_name&&this.formOrder.address&&this.formOrder.payment_type?this.isOk=!0:this.isOk=!1:this.isOk=!0},onSelectPayment:function(t){this.paymentSelected=t},submitOrder:function(){var t=this;this.$store.commit("SET_LOADING",!0),this.storeOrder(this.formOrder).then((function(e){t.$store.commit("SET_LOADING",!1),t.$store.commit("cart/REMOVE_COUPON"),200==e.status&&(t.$store.commit("order/SET_INVOICE",e.data.results),setTimeout((function(){t.$store.dispatch("cart/clearCart",t.session_id)}),1e3),"COD"==t.formOrder.shipping_courier_name&&t.directChekout(e.data.results),t.$router.push({name:"UserInvoice",params:{order_ref:e.data.results.order_ref}}),t.sendMessageNotification(e.data.results.order_ref))})).catch((function(){t.ready=!1,t.$store.commit("SET_LOADING",!1)}))},sendMessageNotification:function(t){var e=this;setTimeout((function(){Object(o["a"])().post("sendNotify",{url:e.getRoutePath(t),order_ref:t})}),12e3)},formatAddressCod:function(t){var e=t.split("<br>");return e.join("\n")},directChekout:function(t){var e=this,a="https://api.whatsapp.com";this.$q.platform.is.desktop&&(a="https://web.whatsapp.com");var s=this.formatPhoneNumber(this.shop.phone),n="Halo kak, saya mau order:\n\n",r=t.items,i=1;r.forEach((function(t){n+="*".concat(i,". ").concat(t.name,"*\n"),t.note&&(n+="[".concat(t.note,"]\n")),n+="Jumlah: ".concat(t.quantity,"\nHarga (@): ").concat(e.moneyIDR(t.price),"\nHarga Total: ").concat(e.moneyIDR(t.quantity*t.price),"\n\n"),i++})),n+="Subtotal: *".concat(this.moneyIDR(t.order_subtotal),"*\nOngkir: *").concat(this.moneyIDR(t.shipping_cost),"*\nDiskon: *").concat(this.moneyIDR(t.discount),"*\nTotal: *").concat(this.moneyIDR(t.order_total),"*\n------------------------\n\n*Nama:*\n ").concat(t.customer_name," (").concat(t.customer_whatsapp,")\n\n*Alamat:*\n").concat(this.formatAddressCod(t.shipping_address),"\n\n"),n+="Metode Pembayaran: ".concat(t.transaction.payment_name,"\n\n"),n+="Referensi Order:\n".concat(this.getRoutePath(t.order_ref));var o=a+"/send?phone="+s+"&text="+encodeURI(n);window.open(o,"_blank")},formatPhoneNumber:function(t){var e=t.replace(/\D/g,"");return e.startsWith("0")&&(e="62"+e.substr(1)),e},getRoutePath:function(t){var e=this.$router.resolve({name:"UserInvoice",params:{order_ref:t}});return location.origin+e.href},getLocalBanks:function(){var t=this;Object(o["a"])().get("banks").then((function(e){200==e.status&&(t.paymentChanels.localbanks=e.data.results)}))},getPaymentChanel:function(){var t=this;Object(o["a"])().get("tripay/payment-chanel").then((function(e){200==e.status&&(t.paymentChanels.paymentGateway=e.data.data)}))},next:function(){this.isCod?this.step=3:this.step+=1},prev:function(){this.isCod?this.step=1:this.step-=1}}),meta:function(){return{title:"Checkout"}}},R=P,I=a("9989"),A=a("e359"),T=a("65c6"),$=a("9c40"),E=a("6ac5"),F=a("f531"),N=a("87fe"),B=a("74f7"),j=a("e669"),L=Object(_["a"])(R,s,n,!1,null,null,null);e["default"]=L.exports;w()(L,"components",{QPage:I["a"],QHeader:A["a"],QToolbar:T["a"],QBtn:$["a"],QToolbarTitle:E["a"],QStepper:F["a"],QStep:N["a"],QInnerLoading:B["a"],QSpinnerFacebook:j["a"]})}}]);