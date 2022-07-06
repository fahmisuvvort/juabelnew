
const routes = [
  {
    path: '/',
    component: () => import('layouts/FrontLayout.vue'),
    children: [
      { path: '', name: 'Home', component: () => import('pages/Index.vue')},
      { path: 'favorite', name: 'ProductFavorite', component: () => import('pages/Product/Favorite.vue')},
      { path: 'products', name: 'ProductIndex', component: () => import('pages/Product/Index.vue') },
      { path: 'products/category/:id', name: 'ProductCategory', component: () => import('pages/Product/ByCategory.vue') },
      { path: 'search-product', name: 'ProductSearch', component: () => import('src/pages/Search/SearchProduct.vue') },
      { path: 'search-order', name: 'OrderSearch', component: () => import('src/pages/Search/SearchOrder.vue') },
      { path: 'posts', name: 'FrontPostIndex', component: () => import('src/pages/Post/FrontPostIndex.vue') },
      { path: 'post/:slug', name: 'FrontPostShow', component: () => import('src/pages/Post/FrontPostShow.vue') },
    ]
  },
  {
    path: '/product',
    component: () => import('src/layouts/BlankLayout.vue'),
    children:[
      {
        path: ':slug',
        name: 'ProductShow',
        component: () => import('pages/Product/Show.vue')
      }
    ]
  },
  { 
    path: '/p',
    component: () => import('src/layouts/BlankLayout.vue'),
    children: [
      { path: 'login', name: 'Login', component: () => import('src/pages/Auth/Login.vue')},
      { path: 'register', name: 'Register', component: () => import('src/pages/Auth/Register.vue')},
      { path: 'forgot-password', name: 'ForgotPassword', component: () => import('src/pages/Auth/ForgotPassword.vue')},
      { path: 'reset-password', name: 'ResetPassword', component: () => import('src/pages/Auth/ResetPassword.vue')},
      { path: 'cart', name: 'Cart', component: () => import('src/pages/Shop/Cart.vue')},
      { path: 'checkout', name: 'Checkout', component: () => import('src/pages/Checkout/Index.vue')},
      { path: 'direct-checkout', name: 'CheckoutWithShipping', component: () => import('src/pages/Checkout/DirectWithShipping.vue')},
      { path: 'invoice/:order_ref', name: 'UserInvoice', component: () => import('src/pages/Invoice/Index.vue')},
    ]
  },
  {
    path: '/form',
    component: () => import('layouts/FormLayout.vue'),
    meta: { requiresSeller: true, requiresAuth: true },
    children: [
      { path: 'product/create', name: 'ProductCreate', component: () => import('pages/Product/Create.vue') },
      { path: 'product/edit/:id', name: 'ProductEdit', component: () => import('pages/Product/Edit.vue')},
    ]
  },
  {
    path: '/form',
    component: () => import('layouts/FormLayout.vue'),
    meta: { requiresAdmin: true, requiresAuth: true },
    children: [
      { path: 'category/form', name: 'CategoryForm', component: () => import('pages/Category/Form.vue')},
      { path: 'category/edit/:id', name: 'CategoryEdit', component: () => import('pages/Category/Edit.vue')},
      { path: 'post/create', name: 'PostCreate', component: () => import('pages/Post/Create.vue')},
      { path: 'post/edit/:id', name: 'PostEdit', component: () => import('pages/Post/Edit.vue')},
      { path: 'config', name: 'Config', component: () => import('pages/Config/Index.vue')},
    ]
  },
  {
    path: '/me',
    component: () => import('layouts/CustomerLayout.vue'),
    meta: { requiresCustomer: true, requiresAuth: true },
    children: [
      { path: '', name: 'CustomerAccount', component: () => import('src/pages/Customer/CustomerAccount.vue') },
      { path: 'order', name: 'CustomerOrder', component: () => import('src/pages/Customer/CustomerOrder.vue') },
    ]
  },
  {
    path: '/seller',
    component: () => import('layouts/SellerLayout.vue'),
    meta: { requiresSeller: true, requiresAuth: true },
    children: [
      { path: '', name: 'SellerHome', component: () => import('src/pages/Seller/Home.vue') },
      { path: 'account', name: 'SellerAccount', component: () => import('src/pages/Seller/Account.vue') },
      { path: 'orders', name: 'SellerOrder', component: () => import('src/pages/Seller/Order.vue') },
      { path: 'produk', name: 'SellerProduct', component: () => import('src/pages/Seller/Product.vue') },
      { path: 'warehouse', name: 'SellerWarehouse', component: () => import('src/pages/Seller/Warehouse.vue') },
    ]
  },
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    meta: { requiresAdmin: true, requiresAuth: true },
    children: [
      { path: 'settings', name: 'Settings', component: () => import('src/pages/Dashboard/Settings.vue') },
      { path: 'account', name: 'Account', component: () => import('src/pages/Account/Index.vue') },
      { path: 'slider', name: 'Slider', component: () => import('src/pages/Slider/Index.vue')},
      { path: 'store', name: 'Shop', component: () => import('src/pages/Shop/Index.vue')},
      { path: 'categories', name: 'CategoryIndex', component: () => import('pages/Category/Index.vue')},
      { path: 'products', name: 'AdminProductIndex', component: () => import('pages/Product/AdminProductIndex.vue')},
      { path: 'posts', name: 'AdminPostIndex', component: () => import('src/pages/Post/AdminPostIndex.vue')},
      { path: 'blocks', name: 'AdminBlockIndex', component: () => import('pages/Block/Index.vue')},
      { path: 'orders', name: 'OrderIndex', component: () => import('pages/Order/Index.vue')},
      { path: 'order/:order_ref', name: 'AdminOrderShow', component: () => import('pages/Order/Detail.vue')},
      { path: 'banks', name: 'BankIndex', component: () => import('pages/Shop/BankAccount.vue')},
      { path: 'users', name: 'UserList', component: () => import('pages/Account/UserList.vue')},
      { path: 'discount', name: 'DiscountIndex', component: () => import('pages/Discount/Index.vue')},
      { path: 'promo', name: 'PromoIndex', component: () => import('pages/Promo/Index.vue')},
      { path: 'promo/:id', name: 'PromoDetail', component: () => import('pages/Promo/Detail.vue')},
      { path: 'sellers', name: 'SellerIndex', component: () => import('pages/Seller/Index.vue')},
    ]
  },
  // Always leave this as last one,
  // but you can also remove it
  // {
  //   path: '*',
  //   component: () => import('pages/Error404.vue')
  // }
]

export default routes
