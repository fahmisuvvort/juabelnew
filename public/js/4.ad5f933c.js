(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[4],{"9a15":function(t,e,s){"use strict";s.r(e);var r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"overflow-hidden"},[s("swiper",{ref:"mySwiper",attrs:{options:t.swiperOptions}},t._l(t.datas,(function(e,r){return s("swiper-slide",{key:r,staticClass:"text-center"},[s("div",{staticClass:"cursor-pointer column items-center",on:{click:function(s){return t.showPost(e)}}},[s("div",{staticClass:"partner-img"},[e.image?s("img",{attrs:{src:e.image_url}}):t._e()]),e.label?s("div",{staticClass:"text-sm q-mt-sm text-weight-medium text-grey-9"},[t._v("\n            "+t._s(e.label)+"\n          ")]):t._e()])])})),1)],1)},i=[],n={name:"PartnerCarousel",props:{datas:Array},data:function(){return{swiperOptions:{slidesPerView:5,spaceBetween:5,freeMode:!0}}},methods:{showPost:function(t){t.post&&this.$router.push({name:"FrontPostShow",params:{slug:t.post.slug}})}}},a=n,o=s("2877"),l=Object(o["a"])(a,r,i,!1,null,null,null);e["default"]=l.exports}}]);