<template>
    <div id="ReservationConsultationElement">


        <b-container>
            <b-alert
                    :show="dismissCountDown"
                    variant="danger"
                    dismissible
                    @dismissed="dismissCountDown=0"
                    @dismiss-count-down="countDownChanged"
            >

                <p class="text-center">{{ alert.status }} {{ alert.error }}: {{ alert.message }}</p>
                <p class="text-right">Cette alerte se fermera dans {{ dismissCountDown }} secondes.</p>
            </b-alert>
        </b-container>


        <b-container fluid class="mt-5 ">

            <b-row class="mr-5 ml-5">


                <b-col cols="2">
                    <NavigationReservationFeuille />
                </b-col>

                <b-col cols="10">
                    <b-row>
                        <b-button variant="outline-secondary" @click="backReservations">‹ retour</b-button>
                        <h1 class="ml-5">Reservation n° {{reservationId}}</h1>
                    </b-row>


                    <div v-if="loading" class="loading text-center mt-5 mb-5">
                        <b-spinner label="loading" class="text-center"></b-spinner>
                        <h5>Chargement, veuillez patienter ... </h5>
                    </div>

                    <div v-else>
                        <b-row align-h="between">
                            <b-col cols="6" class="mt-4">
                                <h3>Informations réservation</h3>
                                <b-card class="mt-4">
                                    <b-card-title>Professeur Référent</b-card-title>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4" class="mt-1">
                                            <label for="refnom">Nom</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="refnom">{{professeur.nom}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="refprenom">Prénom</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="refprenom">{{professeur.prenom}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="reftel">Téléphone</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="reftel">{{professeur.telephone}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="refmail">Mail</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="refmail">{{professeur.mail}}</p>
                                        </b-col>
                                    </b-row>
                                </b-card>
                                <b-card class="mt-4">
                                    <b-card-title>Groupe d'étudiants</b-card-title>
                                    <b-table
                                            striped hover
                                            :items="groupe"
                                            :fields="fieldsRow"
                                            show-empty>

                                    </b-table>
                                </b-card>
                                <b-card class="mt-4">
                                    <b-card-title>Autres</b-card-title>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4" class="mt-1">
                                            <label for="matiere">Matière</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="matiere">{{reservation.matiere}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="annee">Annee</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="annee">{{reservation.annee}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="departement">Département</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="departement">{{departement.nom}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="dep_groupe">Departement du groupe</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="dep_groupe">{{reservation.dep_groupe}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <label for="observations">Observations</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="observations">{{reservation.observation}}</p>
                                        </b-col>

                                    </b-row>
                                </b-card>
                            </b-col>
                            <b-col cols="6" class="mt-4" v-if="fiches!=null">
                                <div>
                                    <b-nav tabs align="center">
                                        <b-nav-item v-for="values in fiches" @click="selectFiche(values)">{{values.id}}</b-nav-item>
                                    </b-nav>
                                </div>

                                <b-card class="mt-4">
                                    <b-card-title>Fiche n°{{fiche_resa.id}}</b-card-title>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4" class="mt-1">
                                            <h5>Dates d'emprunt</h5>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p>{{fiche_resa.date_depart}}</p>
                                        </b-col>
                                        <b-col sm="4" class="mt-1">
                                            <h5>Dates de retour</h5>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p>{{fiche_resa.date_retour}}</p>
                                        </b-col>
                                    </b-row>

                                    <b-table
                                            :items="fillMateriels"
                                            striped hover
                                            :fields="fieldsMateriel"
                                            show-empty
                                            :filter="filter">

                                        <template slot="detail" slot-scope="row">
                                            <b-button variant="info" @click="row.toggleDetails" v-if="row.detailsShowing"><font-awesome-icon :icon="['fas','sort-down']" /></b-button>
                                            <b-button variant="info" @click="row.toggleDetails" v-if="!row.detailsShowing"><font-awesome-icon :icon="['fas','caret-right']" /></b-button>
                                        </template>
                                        <template slot="empty" slot-scope="scope">
                                            <h6 class="text-center">Pas de réservation d'exemplaire.</h6>
                                        </template>

                                        <template slot="row-details" slot-scope="row">
                                            <b-card>
                                                <b-table
                                                        striped hover
                                                        :fields="fieldsExemplaire"
                                                        :items="row.item.exemplaire"
                                                        show-empty selectable>
                                                    <template slot="empty" slot-scope="scope">
                                                        <h6 class="text-center">Pas d'exemplaires choisis.</h6>
                                                    </template>
                                                    <template slot="disponibilite" slot-scope="row">
                                                        <b-badge variant="secondary" v-if="row.value != 'disponible'">{{row.value}}</b-badge>
                                                        <b-badge variant="success" v-if="row.value == 'disponible'">{{row.value}}</b-badge>
                                                    </template>
                                                    <template slot="actions" slot-scope="row" class="text-center">
                                                        <b-button variant="warning" class="mr-1"><font-awesome-icon :icon="['fas','info-circle']" /></b-button>
                                                    </template>
                                                </b-table>
                                            </b-card>

                                        </template>

                                        <template slot="actions" slot-scope="row">
                                            <b-button size="sm" class="mr-1" variant="outline-danger" @click="deleteRow(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                        </template>

                                    </b-table>

                                </b-card>

                            </b-col>
                            <b-col cols="6" class="mt-4" v-else>
                                <h3>Pas de feuilles de réservation.</h3>
                            </b-col>
                        </b-row>
                    </div>


                </b-col>

            </b-row>
        </b-container>


    </div>
</template>

<script>

    import {eventBus} from "../main";
    import NavigationReservationFeuille from "../components/navigation/NavigationReservationFeuille";

    export default {
        name:"ReservationConsultationElement",
        components:{
            NavigationReservationFeuille,
        },
        data(){
            return {
                loading:true,
                reservationId: this.$route.params.id,
                reservation: [],
                fiche_resa:[],
                fiches:[],
                departement:[],
                groupe:[],
                professeur:[],
                fillMateriels:[],

                filter: null,
                fieldsMateriel: [
                    {key:'detail',sortable:false},
                    {key:'constructeur', sortable:true},
                    {key:'modele', sortable:true},
                    {key:'agenda', sortable:false},
                ],

                fieldsRow: [
                    { key: 'nom', sortable:true },
                    { key: 'prenom', sortable:true },
                    { key: 'telephone', sortable:true },
                    { key: 'mail', sortable:false },
                ],

                fieldsExemplaire: [
                    {key:'reference', sortable:true},
                    {key:'num_ex', sortable:true},
                    {key:'disponibilite', sortable:true},
                    {key:'actions', sortable:true},
                ],

                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},
            }
        },
        mounted(){
            this.getReservation();
        },
        methods: {

            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },

            backReservations() {
                this.$router.push({path: '/reservation'});
            },

            getReservation(){
                this.loading=true;
                axios.get('/reservations/'+this.reservationId)
                    .then(response => {
                        this.reservation = response.data.reservation;
                        this.professeur = response.data.reservation.professeur;
                        if(response.data.reservation.fiche_resa.length != 0 )
                        {
                            this.fiches = response.data.reservation.fiche_resa;
                            this.selectFiche(this.fiches['0']);
                            this.fiche_resa = response.data.reservation.fiche_resa[0];
                        }
                        else {
                            this.fiches=null;

                        }
                        response.data.reservation.groupe.forEach(element => {
                           if(element.groupe.referent){
                               element._rowVariant='success';
                           }
                        });
                        this.groupe = response.data.reservation.groupe;
                        this.departement = response.data.reservation.departement;
                        this.loading=false;
                    })
                    .catch(error => {
                        this.$router.push({path:'/notfound'});
                    })
            },

            selectFiche(values){
                this.fillMateriels=[];
                this.fiche_resa=values;
                let present=false;

                //remplissement du tableau de materiel
                values.exemplaire.forEach(element => {
                    if(this.fillMateriels.length === 0) {
                        element.materiel.exemplaire=[];
                        this.fillMateriels.push(element.materiel);
                    }
                    else{
                        present=false;
                        this.fillMateriels.forEach(test => {
                            if(test.id===element.materiel.id){
                                present=true;
                            }
                        });
                        if(!present){
                            element.materiel.exemplaire=[];
                            this.fillMateriels.push(element.materiel);
                        }
                    }
                });
                //remplissement des materiels avec les exemplaires
                values.exemplaire.forEach(element => {
                   this.fillMateriels.forEach(materiel => {
                        if(element.materiel.id === materiel.id ){
                            materiel.exemplaire.push(element);
                        }
                   });
                });
            }


        }
    }
</script>