
export const getFilterPost = (state) => (str) => {
  if(str == 'listing') {
    return state.posts.data.filter(el => el.is_listing == 1)
  }
  if(str == 'promote') {
    return state.posts.data.filter(el => el.is_promote == 1)
  }
  return state.posts.data
}


