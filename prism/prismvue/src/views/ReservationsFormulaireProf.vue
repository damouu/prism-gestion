<template>
    <div id="ReservationConsultation">
        <b-container fluid class="mt-5 ">

            <b-row class="mr-5 ml-5">

                <b-col cols="2">
                    <NavigationReservation />
                </b-col>

                <b-col cols="10">

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


                    <div>


                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Reservation Professeur</h1>
                            </b-col>
                        </b-row>

                        <form>
                            <b-row>
                                <b-col cols="12">
                                    <b-card>
                                        <b-card-title>Responsable du projet</b-card-title>
                                        <b-row>
                                            <b-col cols="12">
                                                <b-form-group label="Nom Prénom" label-for="formProfResponsable">
                                                    <vue-bootstrap-typeahead
                                                            id="formProfResponsable"
                                                            :data="responsables"
                                                            v-model="query"
                                                            :serializer="item => item.nom +' '+item.prenom"
                                                            placeholder="Nom du responsable"
                                                            @hit="selectResp($event)"/>
                                                    <template slot="suggestion" slot-scope="{data, htmlText}">
                                                        <div><span class="mr-3" v-html="htmlText"></span></div>
                                                    </template>
                                                </b-form-group>
                                            </b-col>


                                            <b-col cols="3">
                                                <b-form-group label="Nom *" label-for="formProfResponsableNom">
                                                    <b-form-input
                                                            id="formProfResponsableNom"
                                                            v-model="formProf.responsableNom"
                                                            v-validate="{required:true, regex:/^[a-zé7àùûêâôëîï\s-]{1}[a-zé7àùûêâôëîï\'-]*[a-zé7àùûêâôëîï]$/i}"
                                                            data-vv-name="responsableNom"
                                                            :state="validateState('responsableNom')"
                                                            aria-describedby="invalidResponsableNom"
                                                            placeholder="Nom du responsable"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidResponsableNom">Veuillez entrer un nom valide</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Prénom *" label-for="formProfResponsablePrenom">
                                                    <b-form-input
                                                            id="formProfResponsablePrenom"
                                                            v-model="formProf.responsablePrenom"
                                                            v-validate="{required:true, regex:/^[a-zé7àùûêâôëîï\s-]{1}[a-zé7àùûêâôëîï\'-]*[a-zé7àùûêâôëîï]$/i}"
                                                            data-vv-name="responsablePrenom"
                                                            :state="validateState('responsablePrenom')"
                                                            aria-describedby="invalidResponsablePrenom"
                                                            placeholder="Prénom du responsable"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidResponsablePrenom">Veuillez entrer un prénom valide</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Téléphone *" label-for="formProfResponsableTel">
                                                    <b-form-input
                                                            id="formProfResponsableTel"
                                                            v-model="formProf.responsableTel"
                                                            v-validate="{required:true,max:20, numeric:true}"
                                                            data-vv-name="responsableTel"
                                                            :state="validateState('responsableTel')"
                                                            aria-describedby="invalidResponsableTel"
                                                            placeholder="Téléphone du responsable"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidResponsableTel">Veuillez entrer un numero de telephone valide</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Mail *" label-for="formProfResponsableMail">
                                                    <b-form-input
                                                            id="formProfResponsableMail"
                                                            v-model="formProf.responsableMail"
                                                            v-validate="{required:true, email:true}"
                                                            data-vv-name="responsableMail"
                                                            :state="validateState('responsableMail')"
                                                            aria-describedby="invalidResponsable"
                                                            placeholder="mail du responsable"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidResponsableMail">Veuillez entrer un mail valide</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                    </b-card>
                                </b-col>

                                <b-col cols="12"  class="mt-4">
                                    <b-card
                                            title="Information réservations">
                                        <b-row>
                                            <b-col cols="3">
                                                <b-form-group label="Département *" label-for="formProfDepartement">
                                                    <b-form-select
                                                            id="formProfDepartement"
                                                            v-model="formProf.departement"
                                                            v-validate="{required:true}"
                                                            data-vv-name="departement"
                                                            :state="validateState('departement')"
                                                            aria-describedby="invalidDepartement">
                                                        <option v-for="option in departement" v-bind:value="option.value">
                                                            {{ option.text }}
                                                        </option>
                                                        <b-form-invalid-feedback id="invalidDepartemnt">Choisissez un département</b-form-invalid-feedback>
                                                    </b-form-select>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Matière *" label-for="formProfMatiere">
                                                    <b-form-input
                                                            id="formProfMatiere"
                                                            v-model="formProf.matiere"
                                                            v-validate="{required:true}"
                                                            data-vv-name="matiere"
                                                            :state="validateState('matiere')"
                                                            aria-describedby="invalidMatiere"
                                                            placeholder="Matière"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidMatiere">Veuillez choisir une matiere</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Année *" label-for="formProfAnnee">
                                                    <b-form-input
                                                            id="formProfAnnee"
                                                            v-model="formProf.annee"
                                                            v-validate="{required:true}"
                                                            data-vv-name="annee"
                                                            :state="validateState('annee')"
                                                            aria-describedby="invalidAnnee"
                                                            placeholder="Annee"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidAnnee">Veuillez écrire l'année étudiante</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="3">
                                                <b-form-group label="Groupe *" label-for="formProfGroupe">
                                                    <b-form-input
                                                            id="formProfGroupe"
                                                            v-model="formProf.dep_groupe"
                                                            v-validate="{required:true}"
                                                            data-vv-name="groupe"
                                                            :state="validateState('groupe')"
                                                            aria-describedby="invalidGroupe"
                                                            placeholder="Groupe"
                                                            type="text">
                                                    </b-form-input>
                                                    <b-form-invalid-feedback id="invalidGroupe">Veuillez écrire le groupe de classe</b-form-invalid-feedback>
                                                </b-form-group>
                                            </b-col>

                                            <b-col cols="12">
                                                <b-form-group label="Observations" label-for="formProfObservation">
                                                    <b-form-textarea
                                                            id="formProfObservation"
                                                            v-model="formProf.observation"
                                                            placeholder="Ecrivez quelque chose..."
                                                            rows="2"
                                                            max-rows="6"
                                                    ></b-form-textarea>
                                                </b-form-group>

                                            </b-col>

                                        </b-row>
                                    </b-card>
                                </b-col>

                                <span class="text-danger mt-4">* champs obligatoires</span>

                                <b-col md="3" offset-md="9">
                                    <b-button variant="outline-success" class="mt-4 mb-5" @click="next">Passer à la réservation</b-button>
                                </b-col>
                            </b-row>
                        </form>

                    </div>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>


<script>

    import _ from 'underscore';
    import {eventBus} from "../main";
    import NavigationReservation from '../components/navigation/NavigationReservation'
    import ModalAddReservationEtudiant from "../components/modals/ModalAddReservationEtudiant";


    export default {
        name:"ReservationConsultation",
        components:{
            NavigationReservation,
            ModalAddReservationEtudiant
        },
        data() {
            return {
                fields: [
                    { key: 'nom', sortable:false },
                    { key: 'prenom', sortable:false },
                    { key: 'mail', sortable:false },
                    { key: 'telephone', sortable:false },
                    { key: 'actions', sortable:false },
                ],
                etudiants: [],

                formProf: [],
                query: '',

                departement: [],
                departements: [],
                responsables: [],
                selectedResponsable: null,
                chosenResponsable: '',

                sortBy : 'id',
                sortDesc: false,
                mode: 'single',
                selected: [],

                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},
            }
        },
        mounted() {
            this.getDepartements();
            this.getResponsables();
            eventBus.$on('error',data => {
                this.showAlert(data.error, data.status, data.message);
            });
        },
        watch: {
            query: _.debounce(function(newQuery) {
                this.getResponsables(newQuery);
            },250),
        },
        methods: {

            next(){
                this.$validator.validateAll().then( result => {
                    if (!result) {
                        return;
                    } else
                    {
                        if(!this.formProf.responsable_projet)
                        {
                            axios.post('/professeurs', {
                                'nom': this.formProf.responsableNom,
                                'prenom': this.formProf.responsablePrenom,
                                'mail': this.formProf.responsableMail,
                                'telephone': this.formProf.responsableTel
                            })
                                .then( response => {
                                    this.formProf.responsable_projet = response.data.etudiant.id;
                                })
                                .catch(error => {
                                    console.log(error);
                                })
                        }
                        else
                        {
                            axios.put('/professeurs/'+this.formProf.responsable_projet, {
                                'nom': this.formProf.responsableNom,
                                'prenom': this.formProf.responsablePrenom,
                                'mail': this.formProf.responsableMail,
                                'telephone': this.formProf.responsableTel
                            })
                                .then( response => {
                                    this.formProf.responsable_projet = response.data.professeur.id;
                                })
                                .catch(error => {
                                    console.log(error);
                                })
                        }

                        axios.post('/reservations', {
                            'responsable_projet': this.formProf.responsable_projet,
                            'departement': this.formProf.departement,
                            'matiere': this.formProf.matiere,
                            'annee': this.formProf.annee,
                            'dep_groupe': this.formProf.dep_groupe,
                            'observation': this.formProf.observation,
                        })
                            .then( response => {
                                console.log(response.data);
                                this.$router.push({path: '/reservation/'+ response.data.reservation.id +'/feuille'});
                            })
                            .catch(error => {
                                console.log('TEST');
                                console.log(error);
                            })
                    }
                });
            },


            back(){
                this.formulaire = true;
            },

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            getDepartements(){
                axios.get('/departements')
                    .then( response => {
                        this.departements = response.data.departements;
                        this.departements.forEach(element => {
                            this.departement.push({'value':element.id, 'text': element.nom});
                        });
                    })
                    .catch(error => {
                        console.log(error.response);
                    })
            },

            getResponsables(newQuery) {
                axios.get('/professeurs?query='+newQuery)
                    .then( response => {
                        this.responsables=[];
                        response.data.professeurs.forEach( element =>  {
                            this.responsables.push(element);
                        });
                    })
                    .catch( error => {
                        console.log(error.response);
                    })
            },

            selectResp($event) {
                this.$set(this.formProf,'responsableNom',$event['nom']);
                this.$set(this.formProf,'responsablePrenom',$event['prenom']);
                this.$set(this.formProf,'responsableTel',$event['telephone']);
                this.$set(this.formProf,'responsableMail',$event['mail']);
                this.$set(this.formProf,'responsable_projet',$event['id']);
            },


            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },
        }

    }
</script>