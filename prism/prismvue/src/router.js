import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/HomeVue'
import Inventaire from './views/InventaireVue'
import Reservations from './views/ReservationsVue'
import Materiel from './views/MaterielVue'
import Fournisseur from './views/FournisseurVue'
import Fournisseurs from './views/FournisseursVue.vue'
import Reformes from './views/ReformesVue'
import Reforme from './views/ReformeVue'
import Feuille_Reservation from './views/ReservationFormulaireMaterielEtu'

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
      path: '/inventaire/materiel/:id',
      name: 'materiel',
      component: Materiel,
    },
    {
      path: '/inventaire/fournisseurs',
      name: 'fournisseurs',
      component: Fournisseurs
    },
    {
      path: '/inventaire/fournisseur/:id',
      name: 'fournisseur',
      component: Fournisseur,
    },
    {
      path: '/inventaire/reformes',
      name: 'reformes',
      component: Reformes,
    },
    {
      path: '/inventaire/reforme/:id',
      name: 'reforme',
      component: Reforme,
    },
    {
      path: '/reservation',
      name: 'reservations',
      component: Reservations,
    }
    ,
    {
      path: '/reservation/:id/feuille',
      name: 'fiche_reservation',
      component: Feuille_Reservation,
    }
  ]
})
