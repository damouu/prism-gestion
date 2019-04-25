import Vue from 'vue'
import Router from 'vue-router'
import Home from './components/HomeVue'
import Inventaire from './components/InventaireVue'
import Materiel from './components/MaterielVue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/inventaire',
      name: 'Inventaire',
      component: Inventaire,
    },
    {
      path: '/materiel/:id',
      name: 'materiel',
      component: Materiel,
    }
  ]
})
