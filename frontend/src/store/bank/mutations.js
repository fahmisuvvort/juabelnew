
export function SET_BANKS (state, payload) {

  state.banks.data = payload
  state.banks.ready = true
  state.banks.available = payload.length ? true : false
}

