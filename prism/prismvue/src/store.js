import Vue from 'vue'
import Vuex from 'vuex'


Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        materielId: true,
        exemplaireId: false,
        uNetID: '',
        uNetLvl: '',
        JWT: '',
    },
    mutations: {
        JWT(state, JWT) {
            state.JWT = JWT
        },
        uNetLvl(state, uNetLvl) {
            state.uNetLvl = uNetLvl
        },
        uNetID(state, uNetID) {
            state.uNetID = uNetID
        },
        selectMateriel(state, data) {
            state.materielId = data.id;
        },

        selectExemplaire(state, data) {
            state.exemplaireId = data;
        },

        initializeStore(state) {
            if (localStorage.getItem('store')) {
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store')))
                );
            }
        }

    },
    getters: {
        JWT: state => state.JWT,
        uNetLvl: state => state.uNetLvl,
        uNetID: state => state.uNetID
    },
    actions: {}
})
