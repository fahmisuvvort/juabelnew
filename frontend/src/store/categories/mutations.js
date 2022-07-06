
export function SET_CATEGORIES ( state, payload ) {
  if(payload.length) {
    state.categories.data = payload
  }
  state.categories.ready = true
  state.categories.available = payload.length > 0 ? true : false
}
export function SET_ALL_CATEGORIES ( state, payload ) {
  if(payload.length) {
    state.categoryAll.data = payload
  }
  state.categoryAll.ready = true
  state.categoryAll.available = payload.length > 0 ? true : false
}
export function UPDATE_CATEGORIES ( state, payload ) {
  state.categories = state.categories.filter(el => el.id != payload)
}
export function SET_CATEGORY( state, payload ) {
  state.categories.push(payload)
}
export function REMOVE_CATEGORY ( state, payload ) {
  state.categories = state.categories.filter(el => el.id != payload)
}
export function SET_LOADER_CATEGORY ( state ) {
  state.categories.ready = false
  state.categories.available = true
}
