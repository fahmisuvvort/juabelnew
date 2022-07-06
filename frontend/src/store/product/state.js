export default function () {
  return {
    products: {
      data: [],
      ready: false,
      available: true
    },
    admin_products: {
      item: {
        data: [],
      },
      ready: false,
      available: true
    },
    initial_products: {
      data: [],
      ready: false,
      available: true
    },
    favorites: [],
    productSearch: {
      data: [],
      ready: false,
      available: true
    },
    searchKey: '',
    productByCategory: {
      data: [],
      ready: false,
      available: true,
      links: null,
      meta: null
    },
    productFavorites: {
      data: [],
      ready: false,
      available: true
    },
    product_promo: []
  }
}
