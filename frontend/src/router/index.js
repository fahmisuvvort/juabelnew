import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default function ( { store } ) {
  const Router = new VueRouter({
    // scrollBehavior: () => ({ x: 0, y: 0 }),
    scrollBehavior: (to, from, savedPosition) => {
      return new Promise((resolve, reject) => {
        if (savedPosition) {
          resolve(savedPosition)
        } else {
          resolve({ x: 0, y: 0 })
        } 
      })
    },
    routes,

    // Leave these as they are and change in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  function isLoggedIn() {
    return store.state.user.loggedUser
  }
  function isAdmin() {
    return store.getters['user/isAdmin']
  }

  function isSeller() {
    return store.getters['user/isSeller']
  }

  Router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {

      if(!isLoggedIn) {

        next({
          path: '/p/login',
        })

      } else {

        if (to.matched.some(record => record.meta.requiresAdmin)) {

        // alert('this is requires admin')
  
           if(isAdmin()) { 

            next()
  
           } else {
  
            if(isSeller()) {
  
              next({
                name: 'SellerHome'
              })
  
            }else {
              
              next({
                path: '/me/order'
              }) 
            }
           }    
        }
        if (to.matched.some(record => record.meta.requiresSeller)) {
  
           if(isSeller() || isAdmin()) { 

            next()
  
           } else {

             next({
               path: '/me/order'
             }) 

           }    
        }
        next()
      }

    } else {
      next()
    }
  })


  return Router
}
