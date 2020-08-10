import Vue from 'vue'
import store from '@/js/store'
import router from '@/js/router'
import Buefy from 'buefy'
import '@mdi/font/css/materialdesignicons.min.css'

import { ValidationObserver, ValidationProvider } from 'vee-validate'
import FormInput from '@/js/components/form/Input'

import App from '@/js/App'

Vue.use(Buefy)

Vue.component('ValidationObserver', ValidationObserver)
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('FormInput', FormInput)

store.dispatch('auth/me').then(() => {
  new Vue({
    store,
    router,
    el: '#app',
    render: (h) => h(App),
  })
})
