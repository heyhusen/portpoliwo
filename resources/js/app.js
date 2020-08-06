import Vue from 'vue'
import router from './router'
import Buefy from 'buefy'
import '@mdi/font/css/materialdesignicons.min.css'

import { ValidationObserver, ValidationProvider } from 'vee-validate'
import FormInput from './components/form/Input.vue'

import App from './App.vue'

Vue.use(Buefy)

Vue.component('ValidationObserver', ValidationObserver)
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('FormInput', FormInput)

new Vue({
  router,
  el: '#app',
  render: (h) => h(App),
})
