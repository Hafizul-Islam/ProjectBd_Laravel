
require('./bootstrap');

window.Vue = require('vue');

//Add moment js
require('./filter');

//Import View Router
import VueRouter from 'vue-router'
Vue.use(VueRouter)


//support vuex
import Vuex from 'vuex'
Vue.use(Vuex)
import storeData from "./store/index"

const store = new Vuex.Store(
   storeData
)

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

//Routes
import { routes } from './router';

//Register Routes
const router = new VueRouter({
    routes,
})

/*Vue.component('example-component', require('./components/ExampleComponent.vue').default);*/

Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

const app = new Vue({
    el: '#app',
    router,
    store
});
