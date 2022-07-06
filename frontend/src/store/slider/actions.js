import { Api } from 'boot/axios'
import { Notify } from 'quasar'
 
export function getSliders(context) {
  Api().get('sliders').then(response => {
    context.commit('SET_SLIDERS', response.data.results)
  })
}

export function removeSlider( context, id ) {
  context.commit('SET_DATA_STATUS', false)
  Api().delete('sliders/' + id).then(() => {
    context.dispatch('getSliders')
    context.commit('REMOVE_SLIDER', id)
    Notify.create({
      type: 'positive',
      message: 'Berhasil menghapus data'
    })
  }).catch(() => {
    context.commit('SET_DATA_STATUS', true)
  })
}

export function updateSliderWeight ( context, payload) {
  context.commit('SET_DATA_STATUS', false)
 Api().post('update-slider-weight', payload).then(() => {
    context.dispatch('getSliders')
  }).catch(() => {
    context.commit('SET_DATA_STATUS', true)
  })
}