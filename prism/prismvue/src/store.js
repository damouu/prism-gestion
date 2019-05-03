import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {

    materielId: false,
    exemplaireId: false,

  },
  mutations: {

    selectMateriel(state, data) {
      state.materielId = data.id;
    },

    selectExemplaire(state, data) {
      state.exemplaireId = data;
    },


    initializeStore(state) {
      if(localStorage.getItem('store')) {
        this.replaceState(
            Object.assign(state, JSON.parse(localStorage.getItem('store')))
        );
      }
    }

  },
  actions: {

  }
})
