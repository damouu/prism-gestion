<template>
    <div id="ReservationFormulaireEtu">

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

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Reservation Etudiante</h1>
            </b-col>
        </b-row>

                <form>
                    <b-row>
                        <b-col cols="12">
                            <b-card>
                                <b-card-title>Responsable du projet</b-card-title>
                                <b-row>
                                    <b-col cols="12">
                                        <b-form-group label="Nom Prénom" label-for="formEtuResponsable">
                                            <vue-bootstrap-typeahead
                                                    id="formEtuResponsable"
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
                                        <b-form-group label="Nom *" label-for="formEtuResponsableNom">
                                            <b-form-input
                                                    id="formEtuResponsableNom"
                                                    v-model="formEtu.responsableNom"
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
                                        <b-form-group label="Prénom *" label-for="formEtuResponsablePrenom">
                                            <b-form-input
                                                    id="formEtuResponsablePrenom"
                                                    v-model="formEtu.responsablePrenom"
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
                                        <b-form-group label="Téléphone *" label-for="formEtuResponsableTel">
                                            <b-form-input
                                                    id="formEtuResponsableTel"
                                                    v-model="formEtu.responsableTel"
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
                                        <b-form-group label="Mail *" label-for="formEtuResponsableMail">
                                            <b-form-input
                                                    id="formEtuResponsableMail"
                                                    v-model="formEtu.responsableMail"
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
                                        <b-form-group label="Département *" label-for="formEtuDepartement">
                                            <b-form-select
                                                    id="formEtuDepartement"
                                                    v-model="formEtu.departement"
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
                                        <b-form-group label="Matière *" label-for="formEtuMatiere">
                                            <b-form-input
                                                    id="formEtuMatiere"
                                                    v-model="formEtu.matiere"
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
                                        <b-form-group label="Année *" label-for="formEtuAnnee">
                                            <b-form-input
                                                    id="formEtuAnnee"
                                                    v-model="formEtu.annee"
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
                                        <b-form-group label="Groupe *" label-for="formEtuGroupe">
                                            <b-form-input
                                                    id="formEtuGroupe"
                                                    v-model="formEtu.dep_groupe"
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
                                        <b-form-group label="Observations" label-for="formEtuObservation">
                                            <b-form-textarea
                                                    id="formEtuObservation"
                                                    v-model="formEtu.observation"
                                                    placeholder="Ecrivez quelque chose..."
                                                    rows="2"
                                                    max-rows="6"
                                            ></b-form-textarea>
                                        </b-form-group>

                                    </b-col>

                                </b-row>
                            </b-card>
                        </b-col>

                        <b-col cols="12" class="mt-4">
                            <b-card>
                                <b-card-title>Etudiant Référent, Groupe d'élèves  <b-button pill variant="outline-info" v-b-modal.modal-AddResaEtu>Ajouter un élève</b-button></b-card-title>

                                <b-form-group label="Nom et Prénom d'élève" label-for="formEtuEtudiant">
                                    <vue-bootstrap-typeahead
                                            id="formEtuEtudiant"
                                            :data="etudiant"
                                            v-model="queryEtudiant"
                                            :serializer="item => item.nom +' '+item.prenom"
                                            placeholder="Nom et prénom de l'élève"
                                            @hit="selectRespEleve($event)"/>
                                    <template slot="suggestion" slot-scope="{data, htmlText}">
                                        <div><span class="mr-3" v-html="htmlText"></span></div>
                                    </template>

                                </b-form-group>

                                <b-table
                                        :items="etudiants"
                                        striped hover
                                        :fields="fields"
                                        show-empty
                                        class="mt-4">

                                    <template slot="empty" slot-scope="scope">
                                        <h6 class="text-center">Pas d'étudiants à afficher.</h6>
                                    </template>

                                    <template slot="actions" slot-scope="row">
                                        <b-button size="sm" class="mr-1" variant="outline-danger" @click="deleteRow(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                        <b-button size="sm" v-show="!(row.item.id === etudiants[0].id)" class="mr-1" @click="moveUpRow(row.item)"><font-awesome-icon :icon="['fas','sort-up']" /></b-button>
                                        <b-button size="sm" v-show="!(row.item.id === etudiants[(etudiants.length)-1].id)" @click="moveDownRow(row.item)"><font-awesome-icon :icon="['fas','sort-down']" /></b-button>
                                    </template>

                                </b-table>

                            </b-card>
                        </b-col>

                        <span class="text-danger mt-4">* champs obligatoires</span>

                        <b-col md="3" offset-md="9">
                            <b-button variant="outline-success" class="mt-4 mb-5" @click="next">Passer à la réservation</b-button>
                        </b-col>
                    </b-row>
                </form>

        <ModalAddReservationEtudiant />

    </div>
</template>


<script>



    import _ from 'underscore';
    import {eventBus} from "../main";
    import ModalAddReservationEtudiant from "./modals/ModalAddReservationEtudiant";

    export default {
        name: 'ReservationFormulaireEtu',
        components: {
            ModalAddReservationEtudiant,
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

                formEtu: [],
                query: '',
                queryEtudiant: '',
                etudiant: [],

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
            eventBus.$on('addedEtu', data => {
                let $event = data;
               this.selectRespEleve($event);
            });
        },
        watch: {
            query: _.debounce(function(newQuery) {
                this.getResponsables(newQuery);
            },250),

            queryEtudiant: _.debounce(function(newQuery) {
                this.getEtudiants(newQuery);
            },250),
        },
        methods: {

            next(){
                if(this.etudiants.length === 0)
                {
                    this.$validator.validateAll().then( result => {
                        if (!result) {
                            return;
                        }
                    });
                }
                else {
                    this.$validator.validateAll().then( result => {
                        if (!result) {
                            return;
                        } else
                        {

                            if(!this.formEtu.responsable_projet)
                            {
                                axios.post('/professeurs', {
                                    'nom': this.formEtu.responsableNom,
                                    'prenom': this.formEtu.responsablePrenom,
                                    'mail': this.formEtu.responsableMail,
                                    'telephone': this.formEtu.responsableTel
                                })
                                    .then( response => {
                                        this.formEtu.responsable_projet = response.data.etudiant.id;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    })
                            }
                            else
                            {
                                axios.put('/professeurs/'+this.formEtu.responsable_projet, {
                                    'nom': this.formEtu.responsableNom,
                                    'prenom': this.formEtu.responsablePrenom,
                                    'mail': this.formEtu.responsableMail,
                                    'telephone': this.formEtu.responsableTel
                                })
                                    .then( response => {
                                        this.formEtu.responsable_projet = response.data.professeur.id;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    })

                            }
                            let etu = [];
                            this.etudiants.forEach(element => {
                                etu.push(element['id']);
                            });
                            axios.post('/reservations', {
                                'responsable_projet': this.formEtu.responsable_projet,
                                'departement': this.formEtu.departement,
                                'matiere': this.formEtu.matiere,
                                'annee': this.formEtu.annee,
                                'dep_groupe': this.formEtu.dep_groupe,
                                'observation': this.formEtu.observation,
                                'groupes': etu
                            })
                                .then( response => {
                                    this.$router.push({path: '/reservation/'+ response.data.reservation.id +'/feuille'});
                                })
                                .catch(error => {
                                    console.log(error.response);
                                })


                        }

                    });
                }

            },


            back(){
                this.formulaire = true;
            },


            validate()
            {
                console.log('c\'est envoyé');
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

            getEtudiants(newQuery) {
                axios.get('/etudiants?query='+newQuery)
                    .then( response => {
                        this.etudiant = [];
                        response.data.etudiants.forEach( element => {
                            this.etudiant.push(element);
                        });
                    })
                    .catch( error => {
                        console.log(error.response);
                    })
            },

            selectResp($event) {
                this.$set(this.formEtu,'responsableNom',$event['nom']);
                this.$set(this.formEtu,'responsablePrenom',$event['prenom']);
                this.$set(this.formEtu,'responsableTel',$event['telephone']);
                this.$set(this.formEtu,'responsableMail',$event['mail']);
                this.$set(this.formEtu,'responsable_projet',$event['id']);
            },

            selectRespEleve($event) {
                let test = false;
                if(this.etudiants.length >= 1){
                    this.etudiants.forEach( element => {
                        if(element.id === $event.id)
                        {
                            test = true;
                        }
                    });
                }
                if(test===false) {
                    this.etudiants.push($event);
                }
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


            deleteRow(items) {
                for ( const [key,value] of Object.entries(this.etudiants)){
                    if(value.id === items.id) {
                        this.etudiants.splice(key,1);
                        break;
                    }
                }
            },
            moveUpRow(items) {
                console.log(items);
                for ( const [key,value] of Object.entries(this.etudiants)){
                    if(value.id === items.id) {
                        if(key != 0 ){
                            let del = this.etudiants.splice(key,1);
                            let keyM = key-1;
                            this.etudiants.splice(keyM,0,del[0]);
                            break;
                        }
                    }
                }
            },
            moveDownRow(items) {
                let max = Object.keys(this.etudiants).length;
                for ( const [key,value] of Object.entries(this.etudiants)){
                    if(value.id === items.id) {
                        if(key != (max-1) ){
                            let del = this.etudiants.splice(key,1);
                            let keyM = key+1;
                            this.etudiants.splice(keyM,0,del[0]);
                            break;
                        }
                    }
                }
            },

        }

    }
</script>