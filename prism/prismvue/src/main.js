import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import VeeValidate from 'vee-validate'

Vue.config.productionTip = false;

Vue.use(BootstrapVue);

Vue.use(VeeValidate, {
  inject: true,
  fieldsBagName: 'veeFields'
});



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
