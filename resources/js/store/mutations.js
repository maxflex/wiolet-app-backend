export default {
  // static data from Models/Data
  setData(state, data) {
    state.data = data
  },

  setUser(state, user) {
    state.user = user
  },

  setCounters(state, counters) {
    state.counters = counters
  },

  clearSearch(state) {
    state.search = {
      query: '',
      results: null,
    }
  },

  setSearchResults(state, data) {
    state.search.results = data
  },

  set(state, {field, payload}) {
    state[field] = payload
  },

  toggleDrawer(state, value = null) {
    if (value !== null) {
      state.drawer = value
    } else {
      state.drawer = !state.drawer
    }
    localStorage.setItem('drawer', state.drawer)
  },

  // depricated?
  loading(state, value) {
    state.loading = value
  }
}
