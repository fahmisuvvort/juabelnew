
export function SET_ALL_POST (state, payload) {
  state.posts.data = payload
  state.posts.ready = true
  state.posts.available = payload.length > 0 ? true : false
}
export function SET_LISTING_POST (state, payload) {
  state.listing_posts.data = payload
  state.listing_posts.ready = true
  state.listing_posts.available = payload.length > 0 ? true : false
}
export function SET_INITIAL_POST (state, payload) {
  state.initialPost = payload
}
export function SET_LOADER_POST (state) {
  state.posts.ready = false
  state.posts.available = true
}


