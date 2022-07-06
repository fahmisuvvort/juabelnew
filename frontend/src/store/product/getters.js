export const getProductById = (state) => (id) => {
  let p = state.products.data.find(el => el.id == id)
  if(p != undefined) {
    return p
  } else {
    return null
  }
}

export const favoriteCount = (state) => {
  return state.favorites.length
}
export const productCount = (state) => {
  return state.products.data.length
}

