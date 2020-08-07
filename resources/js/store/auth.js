import $axios from '@/js/api.js'

export default {
  namespaced: true,

  state: {
    authenticated: false,
    user: null,
  },

  getters: {
    authenticated(state) {
      return state.authenticated
    },
    user(state) {
      return state.user
    },
  },

  mutations: {
    setAuthenticated(state, value) {
      state.authenticated = value
    },
    setUser(state, value) {
      state.user = value
    },
  },

  actions: {
    async logIn({ dispatch }, credentials) {
      await $axios.get('/sanctum/csrf-cookie')
      await $axios.post('/login', credentials)

      return dispatch('me')
    },

    async logOut({ dispatch }) {
      await $axios.get('/sanctum/csrf-cookie')
      await $axios.post('/logout')

      return dispatch('me')
    },

    me({ commit }) {
      return $axios
        .get('/api/account/me')
        .then((response) => {
          commit('setAuthenticated', true)
          commit('setUser', response.data.data)
        })
        .catch(() => {
          commit('setAuthenticated', false)
          commit('setUser', null)
        })
    },
  },
}
