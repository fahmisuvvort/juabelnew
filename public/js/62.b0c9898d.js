(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[62],{"062f":function(t,n,e){"use strict";e.r(n);var r,o=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("q-page",{staticClass:"bg-grey-1 default",class:{"flex flex-center":t.loading}},[e("q-header",{staticClass:"bg-white text-dark box-shadow"},[e("q-toolbar",{staticClass:"items-center sans"},[t.shop?e("img",{staticClass:"logo",attrs:{src:t.shop.logo?t.shop.logo:"/icon/logo.png"}}):t._e(),t.shop&&t.shop.name?e("q-toolbar-title",{staticClass:"text-weight-bold text-primary"},[t._v(t._s(t.shop.name))]):t._e(),e("shopping-cart")],1)],1),t.loading?t._e():[t.sliders.data.length?e("div",{staticClass:"q-pt-sm",attrs:{id:"slider"}},[e("slider",{attrs:{datas:t.sliders.data}})],1):t._e(),t.blocks.featured.length?e("div",{staticClass:"auto-padding-side block-container q-pt-md",attrs:{id:"featured"}},[e("featured-carousel",{attrs:{datas:t.blocks.featured}})],1):t._e(),t.categories&&t.categories.data.length>1?e("div",{staticClass:"auto-padding-side block-container",attrs:{id:"categories"}},[e("div",{staticClass:"block-heading"},[e("div",{staticClass:"block-title"},[e("h2",[t._v("Kategori")])])]),e("div",{staticClass:"block-content q-pb-sm"},[e("category-carousel",{attrs:{datas:t.categories.data}})],1)]):t._e(),t.productPromo.length?e("div",{attrs:{id:"product-promo"}},[e("product-promo",{attrs:{product_promo:t.productPromo}})],1):t._e(),t.banner1?e("div",{staticClass:"banner auto-padding-side block-container"},[e("img",{attrs:{src:t.banner1.image_url},on:{click:function(n){return t.goToPost(t.banner1)}}})]):t._e(),e("product-block",{attrs:{products:t.products}}),t.blocks.partner.length?e("div",{staticClass:"partner auto-padding-side block-container"},[e("div",{staticClass:"block-heading"},[e("div",{staticClass:"block-title"},[e("h2",[t._v("Partners")])])]),e("div",{staticClass:"block-content"},[e("partner-carousel",{attrs:{datas:t.blocks.partner}})],1)]):t._e(),t.banner2?e("div",{staticClass:"banner auto-padding-side block-container"},[e("img",{attrs:{src:t.banner2.image_url},on:{click:function(n){return t.goToPost(t.banner2)}}})]):t._e(),e("post-block",{attrs:{posts:t.posts}}),t.banner3?e("div",{staticClass:"banner auto-padding block-container"},[e("img",{attrs:{src:t.banner3.image_url},on:{click:function(n){return t.goToPost(t.banner3)}}})]):t._e(),e("install-app"),e("footer-block")],e("q-inner-loading",{attrs:{showing:t.loading}},[e("q-spinner-facebook",{attrs:{size:"50px",color:"primary"}})],1)],2)},a=[],i=e("ded3"),s=e.n(i),c=e("9523"),l=e.n(c),d=(e("e260"),e("d3b7"),e("e6cf"),e("3ca3"),e("ddb0"),e("7db0"),e("ac1f"),e("841c"),e("2f62")),u=e("c6c8"),p=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"overflow-hidden"},[e("vue-glide",{attrs:{options:t.glideOptions}},t._l(t.datas,(function(t,n){return e("vue-glide-slide",{key:n},[e("div",{staticClass:"auto-padding"},[e("img",{staticStyle:{width:"100%",height:"auto","border-radius":"6px"},attrs:{src:t.src}})])])})),1)],1)},b=[],h={name:"Front",props:{datas:Array},data:function(){return{glideOptions:{gap:10,perView:1,animationDuration:1e3,autoplay:6e3,bullet:!0}}}},g=h,f=e("2877"),m=Object(f["a"])(g,p,b,!1,null,null,null),k=m.exports,v=e("3f41"),_={name:"PageIndex",components:(r={ShoppingCart:u["a"],Slider:k,ProductBlock:v["a"],"category-carousel":function(){return e.e(63).then(e.bind(null,"d702"))},"product-promo":function(){return e.e(7).then(e.bind(null,"955b"))},"post-block":function(){return Promise.all([e.e(1),e.e(2)]).then(e.bind(null,"d29a"))},"featured-carousel":function(){return e.e(5).then(e.bind(null,"3990"))},"partner-carousel":function(){return e.e(4).then(e.bind(null,"9a15"))}},l()(r,"post-block",(function(){return Promise.all([e.e(1),e.e(2)]).then(e.bind(null,"d29a"))})),l()(r,"footer-block",(function(){return e.e(6).then(e.bind(null,"bae6"))})),l()(r,"install-app",(function(){return e.e(3).then(e.bind(null,"30b4"))})),r),data:function(){return{viewMode:"grid",search:"",slide:0}},computed:s()(s()({},Object(d["e"])({blocks:function(t){return t.block.blocks},sliders:function(t){return t.slider.sliders},categories:function(t){return t.category.categories},products:function(t){return t.product.initial_products},shop:function(t){return t.shop},loading:function(t){return t.loading},posts:function(t){return t.post.initialPost},config:function(t){return t.config},productPromo:function(t){return t.product.product_promo}})),{},{cheight:function(){this.$q.screen.width;return this.$q.screen.width>600?"400px":this.$q.screen.width/1.5+"px"},banner1:function(){if(this.blocks.banner.length){var t=this.blocks.banner.find((function(t){return 1==t.weight}));return void 0!=t?t:void 0}return null},banner2:function(){if(this.blocks.banner.length){var t=this.blocks.banner.find((function(t){return 2==t.weight}));return void 0!=t?t:void 0}return null},banner3:function(){if(this.blocks.banner.length){var t=this.blocks.banner.find((function(t){return 3==t.weight}));return void 0!=t?t:void 0}return null}}),methods:s()(s()({},Object(d["b"])(["getInitialData"])),{},{showProductByCategory:function(t){this.$router.push({name:"ProductCategory",params:{id:t}})},searchNow:function(){this.search&&""!=this.search&&this.$router.push({name:"ProductSearch",query:{q:this.search}})},goToPost:function(t){t.post&&this.$router.push({name:"FrontPostShow",params:{slug:t.post.slug}})}}),mounted:function(){this.config&&(this.viewMode=this.config.home_view_mode)},created:function(){this.shop&&this.products.data.length&&!this.$route.query.load||this.getInitialData()}},w=_,C=e("9989"),P=e("e359"),q=e("65c6"),y=e("6ac5"),x=e("74f7"),$=e("e669"),T=e("eebe"),O=e.n(T),Q=Object(f["a"])(w,o,a,!1,null,null,null);n["default"]=Q.exports;O()(Q,"components",{QPage:C["a"],QHeader:P["a"],QToolbar:q["a"],QToolbarTitle:y["a"],QInnerLoading:x["a"],QSpinnerFacebook:$["a"]})}}]);