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

import Agenda from './views/AgendaVue'
import Categories from './views/CategoriesVue'

import NotFound from './views/NotFoundVue'

import ReservationsConsultationFiches from "./views/ReservationsConsultationFiches";
import ReservationsFormulaireEtu from "./views/ReservationsFormulaireEtu";
import ReservationsFormulaireProf from "./views/ReservationsFormulaireProf";
import ReservationConsultationElement from "./views/ReservationConsultationElement";
import ReservationFormulaireMaterielEtu from "./views/ReservationFormulaireMaterielEtu";
import ReservationConsultationFicheElement from "./views/ReservationConsultationFicheElement";

Vue.use(Router);

export default new Router({
  routes: [

    { path: '*', component: NotFound },

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
      path: '/inventaire/categories',
      name: 'categories',
      component: Categories,
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
    },

    {
      path: '/reservation/feuilles',
      name: 'reservations_consultation_feuilles',
      component: ReservationsConsultationFiches
    },

    {
      path: '/reservation/etu',
      name: 'creation_reservation_etu',
      component: ReservationsFormulaireEtu
    },
    {
      path: '/reservation/autre',
      name: 'creation_reservation_autre',
      component: ReservationsFormulaireProf
    },
    {
      path: '/reservation/:id',
      name: 'consultation_reservation',
      component: ReservationConsultationElement
    },
    {
      path: '/reservation/:id/feuille',
      name: 'fiche_reservation',
      component: ReservationFormulaireMaterielEtu,
    },
    {
      path: '/reservation/:idresa/feuille/:idfeuille',
      name: 'consultation_reservation_fiche',
      component: ReservationConsultationFicheElement,
    },


    {
      path: '/agenda',
      name: 'agenda',
      component: Agenda
    },


    {
      path: 'notfound',
      name: 'notfound',
      component: NotFound
    }

  ]
})
