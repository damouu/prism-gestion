import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/HomeVue'
import Inventaire from './views/InventaireVue'
import Materiel from './views/MaterielVue'

Vue.use(Router);

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
