export function SET_USER (state, payload) {
  state.user = payload
  state.loggedUser = true
}

export function LOGOUT (state) {
  state.user = false
  state.loggedUser = false
}
