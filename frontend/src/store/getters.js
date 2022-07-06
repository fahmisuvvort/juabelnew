
const getApiUrl = (state) => {
	return state.API_URL
}

const getTheme = (state) => {
  if(state.config && state.config.theme) {
    return state.config.theme
  }
  return 'default'
}

const isDemoMode = (state) => {
  return state.config.is_demo_mode
}

export default {
  getApiUrl,
  getTheme,
  isDemoMode,
}