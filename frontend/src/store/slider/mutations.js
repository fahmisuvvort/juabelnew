export function SET_SLIDERS ( state, payload) {
  state.sliders.data = payload
  state.sliders.ready = true
  state.sliders.available = payload.length > 0 ? true : false
}
export function REMOVE_SLIDER ( state, ids ) {
  state.sliders.data = state.sliders.data.filter(el => el.id != ids)
}
export function SET_DATA_STATUS ( state, status ) {
  state.sliders.ready = status
}
