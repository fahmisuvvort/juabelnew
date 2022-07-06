import { Api } from 'boot/axios'
export function addBlock (context, payload) {
  let formData = new FormData();

  for(const x in payload) {
    if(payload[x]) {
      formData.append(x, payload[x])
    }
  }

  return Api().post('blocks', formData, { headers: {'Content-Type': 'multipart/formData'}})
}
export function updateBlock (context, payload) {

  let formData = new FormData();

  for(const x in payload) {
    if(payload[x]) {
      formData.append(x, payload[x])
    }
  }
  formData.append('_method', 'PUT');

  return Api().post('blocks/' + payload.id, formData, { headers: {'Content-Type': 'multipart/formData'}})
}

export function getBlocks(context) {
  Api().get('blocks').then(response => {
    if(response.status == 200) {
      context.commit('SET_BLOCKS', response.data.results)
    }
  })
}
export function getAdminBlocks(context) {
  Api().get('blocks').then(response => {
    if(response.status == 200) {
      context.commit('SET_ADMIN_BLOCKS', response.data.results)
    }
  })
}
export function getBlockById(context, id) {
  return Api().get('blocks/'+id)
}
export function deleteBlock(context, id) {
  context.commit('REMOVE_BLOCKS', id)
  Api().delete('blocks/'+id).then(res => {
    context.dispatch('getAdminBlocks')
  })
}

