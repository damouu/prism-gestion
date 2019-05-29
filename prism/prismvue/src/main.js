import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import VeeValidate from 'vee-validate'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons'
import { faSortUp } from '@fortawesome/free-solid-svg-icons'
import { faSortDown } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'



Vue.config.productionTip = false;

Vue.use(BootstrapVue);
library.add(faTrashAlt,faSortUp,faSortDown);
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(VeeValidate, {
  inject: true,
  fieldsBagName: 'veeFields'
});

Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead);

export const eventBus = new Vue();

window.axios = axios.create({
  baseURL : 'http://prism.app.local:10080/api/',
  params : {

  },
});

store.subscribe((mutation,state) => {
  localStorage.setItem('store', JSON.stringify(state));
});


new Vue({
  router,
  store,
  render: h => h(App),
  beforeCreate() {
    this.$store.commit('initializeStore');
  }
}).$mount('#app');
