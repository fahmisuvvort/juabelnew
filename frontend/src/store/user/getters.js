
export function isAdmin (state) {

  return state.user && state.user.role == 'admin' ? true : false
}
export function isCustomer (state) {

  return state.user && state.user.role == 'customer' ? true : false
}
export function isSeller (state) {

  return state.user && state.user.role == 'seller'
}
