
export function SET_BLOCKS (state, payload) {
  if(payload.length) {
    state.blocks.banner = payload.filter(el => el.position == 'Banner');
    state.blocks.partner = payload.filter(el => el.position == 'Partner');
    state.blocks.featured = payload.filter(el => el.position == 'Featured');
  }
  state.blocks.ready = true;
  state.blocks.available = payload.length? true : false;
}
export function SET_ADMIN_BLOCKS (state, payload) {
  if(payload.length) {
    state.admin_data.data = payload;
  }
  state.admin_data.ready = true;
  state.admin_data.available = payload.length? true : false;
}

export function SET_LOADER_BLOCK(state) {
  state.admin_data.ready = false
  state.admin_data.available = true
  state.blocks.ready = false
  state.blocks.available = true
}
export function REMOVE_BLOCKS(state, id) {

  state.admin_data.data = state.admin_data.data.filter(el => el.id != id)
  state.admin_data.ready = false
  state.admin_data.available = true
}
