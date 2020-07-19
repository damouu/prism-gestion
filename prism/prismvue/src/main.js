import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import VeeValidate from 'vee-validate'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'


import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'

import {library} from '@fortawesome/fontawesome-svg-core'
import {faTrashAlt} from '@fortawesome/free-solid-svg-icons'
import {faSortUp} from '@fortawesome/free-solid-svg-icons'
import {faSortDown} from '@fortawesome/free-solid-svg-icons'
import {faEdit} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faAngleLeft} from '@fortawesome/free-solid-svg-icons'
import {faAngleRight} from '@fortawesome/free-solid-svg-icons'
import {faCaretRight} from '@fortawesome/free-solid-svg-icons'
import {faCalendarDay} from '@fortawesome/free-solid-svg-icons'
import {faInfoCircle} from '@fortawesome/free-solid-svg-icons'


Vue.config.productionTip = false;
window.axios = require('axios');

Vue.use(BootstrapVue);

library.add(faTrashAlt, faSortUp, faSortDown, faAngleLeft, faAngleRight, faCaretRight, faEdit, faCalendarDay, faInfoCircle);
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(VeeValidate, {
    inject: true,
    fieldsBagName: 'veeFields'
});

Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead);
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

export const eventBus = new Vue();

window.axios = axios.create(
    {
        baseURL: 'https://iutnc-resamat.univ-lorraine.fr/api/',
    });

store.subscribe((mutation, state) => {
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
