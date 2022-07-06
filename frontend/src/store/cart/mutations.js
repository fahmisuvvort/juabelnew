

export function ADD_TO_CART (state, payload) {

  if(state.carts.length) {

    let hasItem = state.carts.find(el => el.sku == payload.sku)

    if(hasItem != undefined) {
  
      let index = state.carts.findIndex(ej => ej.sku == payload.sku)
  
      state.carts[index].quantity += payload.quantity
  
    } else {
      state.carts.push(payload)
    }
  } else {
    state.carts.push(payload)
  }


}

export function UPDATE_CART(state, payload) {

  let objIndex = state.carts.findIndex(el => el.sku == payload.sku)

  state.carts[objIndex].quantity = parseInt(payload.quantity)


}

export function REMOVE_CART(state, payload) {
  state.carts = state.carts.filter(c => c.sku != payload.sku)
}

export function CLEAR_CART (state) {
  state.carts = []
}

export function SET_CARTS (state, payload) {
  state.carts = payload
}

export function COMMIT_CARTS(state) {
  localStorage.setItem('_wacommerce-carts',JSON.stringify(state.carts))
}
export function ROLLBACK_CARTS(state) {
  state.carts = JSON.parse(localStorage.getItem('_wacommerce-carts'))
}
export function SET_COUPON_DISCOUNT (state, payload) {
  state.coupon_discount = payload
}

export function REMOVE_COUPON (state) {
  state.coupon_discount = null
}