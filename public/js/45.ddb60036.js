(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[45],{"36e7":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",{staticClass:"q-pt-md q-pb-xl",class:{"flex flex-center":!t.posts.available}},[s("q-header",{staticClass:"text-primary bg-white"},[s("q-toolbar",[s("q-btn",{attrs:{to:{name:"Home"},flat:"",round:"",dense:"",icon:"arrow_back"}}),s("q-toolbar-title",{staticClass:"text-weight-bold brand"},[t._v("Posts")])],1)],1),t.posts.ready?[t.posts.available?t._l(t.posts.data,(function(e,a){return s("post-list",t._b({key:a},"post-list",e,!1))})):[s("div",[t._v("Tdak ada data")])]]:t._l(4,(function(t){return s("post-list-skeleton",{key:t})}))],2)},o=[],n=s("ded3"),l=s.n(n),i=s("1f6c"),r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"relative bg-white border-b"},[s("div",{staticClass:"row q-pa-md no-wrap"},[s("q-skeleton",{attrs:{width:"100px",height:"100px"}}),s("div",{staticClass:"q-ml-md column flex-1"},[s("div",{staticClass:"column"},[s("q-skeleton",{attrs:{type:"text",width:"20%"}}),s("q-skeleton",{attrs:{height:"15px",width:"94%"}}),s("q-skeleton",{staticClass:"q-mt-xs",attrs:{height:"15px",width:"60%"}})],1),s("div",{staticStyle:{"margin-top":"auto"}},[s("q-skeleton",{attrs:{width:"80px"}})],1)])],1)])},c=[],p={name:"PostListSkeleton"},d=p,u=s("2877"),h=s("293e"),m=s("eebe"),b=s.n(m),g=Object(u["a"])(d,r,c,!1,null,null,null),f=g.exports;b()(g,"components",{QSkeleton:h["a"]});var k=s("2f62"),w={components:{PostList:i["a"],PostListSkeleton:f},computed:l()({},Object(k["e"])({posts:function(t){return t.post.listing_posts}})),created:function(){this.posts.data.length||this.$store.dispatch("post/listingPost")},meta:function(){return{title:"Artikel",meta:{ogTitle:{property:"og:title",content:"Artikel"},ogImage:{property:"og:image",content:this.posts.data.length?this.posts.data[0].image_url:""}}}}},q=w,v=s("9989"),x=s("e359"),_=s("65c6"),y=s("9c40"),C=s("6ac5"),P=Object(u["a"])(q,a,o,!1,null,null,null);e["default"]=P.exports;b()(P,"components",{QPage:v["a"],QHeader:x["a"],QToolbar:_["a"],QBtn:y["a"],QToolbarTitle:C["a"]})}}]);