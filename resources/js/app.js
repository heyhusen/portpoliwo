import Vue from 'vue'
import Buefy from 'buefy'

import App from './components/ExampleComponent.vue'

Vue.use(Buefy)

const vm = new Vue({
    el: '#app',
    render: h => h(App)
});
