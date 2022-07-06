import { Api } from 'boot/axios'

export function addPost({ commit, dispatch }, payload) {
  // console.log(Object.fromEntries(payload));
  // return
  let formData = new FormData();
  for(const x in payload) {
    if(payload[x] !== '') {
      formData.append(x, payload[x])
    }
  }
  let self = this
  commit('SET_LOADER_POST')
  Api().post('posts', formData, {headers: {'content-Type': 'multipart/formData'}}).then(response => {
    if(response.status == 201) {
     dispatch('getAllPost')
      self.$router.push({name: 'AdminPostIndex'})
      commit('SET_LOADING', false, { root: true })
    }
  }).catch(() => {
    commit('SET_LOADING', false, { root: true })
  })
}
export function updatePost (context, payload) {
  context.commit('SET_LOADER_POST')
  let self = this
  let formData = new FormData();
  for(const x in payload) {
    if(payload[x] !== '') {
      formData.append(x, payload[x])
    }
  }
  formData.append('_method', 'PUT')

  Api().post('posts/'+payload.id, formData, {headers: {'content-Type': 'multipart/formData'}}).then(response => {
    if(response.status == 200) {
      self.$router.push({name: 'AdminPostIndex'})
      context.dispatch('getAllPost')
    }
  })
}
export function deletePost (context, id) {
  context.commit('SET_LOADER_POST')
  Api().delete('posts/'+id).then(response => {
    if(response.status == 200) {
      context.dispatch('getAllPost')
    }
  })
}
export function getAllPost (context) {
  Api().get('posts').then(response => {
    if(response.status == 200) {
      context.commit('SET_ALL_POST', response.data.results)
    }
  })
}
export function listingPost (context) {
  Api().get('posts?q=listing').then(response => {
    if(response.status == 200) {
      context.commit('SET_LISTING_POST', response.data.results)
    }
  })
}
export function getSinglePost (context, id) {
  return Api().get('posts/'+ id)
}
export function getSinglePostBySlug (context,slug) {
  return Api().get('post/'+slug)
}

