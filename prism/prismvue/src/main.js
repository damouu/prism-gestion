import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'

Vue.config.productionTip = false;

Vue.use(BootstrapVue);


window.axios = axios.create({
  baseURL : 'http://prism.app.local:10080/api/',
  params : {

  },
});

store.subscribe((mutation,state) => {
  localStorage.setItem('store', JSON.stringify(state));
});


var vm = new Vue({
  router,
  store,
  render: h => h(App),
  beforeCreate() {
    this.$store.commit('initializeStore');
  }
}).$mount('#app');
