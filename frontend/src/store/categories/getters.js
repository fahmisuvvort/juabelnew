export const getById = (state) => (id) => {
  return state.categories.data.find(el => el.id == id)
}

export const categoryCount = (state) => {
  return state.categories.data.length
}
export const getValueOptions = (state) => {
  let data = []
  if(state.categories.data.length > 0) {
    state.categories.data.forEach(el => {
      data.push({label: el.title, value: el.id})
    });
  }
  return data;
}
export const getParentOptions = (state) => {
  let data = [{ value: '', label: 'Pilih'}]
  if(state.categories.data.length > 0) {
    state.categories.data.filter(el => !el.category_id).forEach(el => {
      data.push({label: el.title, value: el.id})
    });
  }

  return data;
}