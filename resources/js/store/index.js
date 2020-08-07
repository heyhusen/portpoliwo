import Vue from 'vue'
import Vuex from 'vuex'
import auth from '@/js/store/auth'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
  },
})
