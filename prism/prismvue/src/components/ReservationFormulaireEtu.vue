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

        <form ref="modifMateriel" @submit.stop.prevent="modifMateriel" >

            <b-row v-show="formulaire">
                <b-col cols="12">
                    <b-card
                            title="Responsable du projet">
                        <b-row>
                            <b-col cols="3">
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

                            <b-col cols="2">
                                <b-form-group label="Téléphone" label-for="formEtuResponsableTel">
                                    <b-form-input
                                            id="formEtuResponsableTel"
                                            v-model="formEtu.responsableTel"
                                            v-validate="{required:true}"
                                            data-vv-name="responsableTel"
                                            :state="validateState('responsableTel')"
                                            aria-describedby="invalidResponsableTel"
                                            placeholder="Téléphone du responsable"
                                            type="text">
                                    </b-form-input>
                                    <b-form-invalid-feedback id="invalidResponsableTel">Veuillez entrer un numero de telephone valide</b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>

                            <b-col cols="4">
                                <b-form-group label="Mail" label-for="formEtuResponsableMail">
                                    <b-form-input
                                            id="formEtuResponsableMail"
                                            v-model="formEtu.responsableMail"
                                            v-validate="{required:true}"
                                            data-vv-name="responsableMail"
                                            :state="validateState('responsableMail')"
                                            aria-describedby="invalidResponsable"
                                            placeholder="mail du responsable"
                                            type="text">
                                    </b-form-input>
                                    <b-form-invalid-feedback id="invalidResponsableMail">Veuillez entrer un mail valide</b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col cols="3">
                                <b-button pill variant="outline-info" v-b-modal.modal-AddResaProf>Ajouter un responsable</b-button>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>

                <b-col cols="12"  class="mt-4">
                    <b-card
                            title="Etudiant Référent">
                        <b-row>
                            <b-col cols="4">
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

                            <b-col cols="4">
                                <b-form-group label="Matière" label-for="formEtuMatiere">
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

                            <b-col cols="4">
                                <b-form-group label="Projet" label-for="formEtuProjet">
                                    <b-form-input
                                            id="formEtuProjet"
                                            v-model="formEtu.projet"
                                            v-validate="{required:true}"
                                            data-vv-name="projet"
                                            :state="validateState('projet')"
                                            aria-describedby="invalidProjet"
                                            placeholder="Projet"
                                            type="text">
                                    </b-form-input>
                                    <b-form-invalid-feedback id="invalidProjet">Veuillez écrire le nom du projet</b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>


                <b-col cols="12">
                    <b-button variant="outline-success"  @click="next">Passer à la réservation</b-button>
                </b-col>
            </b-row>



            <b-row v-show="!formulaire">
                <b-button variant="outline-secondary" @click="back">Revenir en arrière</b-button>
                <b-button variant="success" @click="validate">Valider</b-button>
            </b-row>

        </form>

        <ModalAddReservationProfesseur />

    </div>
</template>


<script>



    import _ from 'underscore';
    import {eventBus} from "../main";
    import ModalAddReservationProfesseur from "./modals/ModalAddReservationProfesseur";

    export default {
        name: 'ReservationFormulaireEtu',
        components: {ModalAddReservationProfesseur},
        data() {
            return {
                formulaire: true,

                formEtu: [],
                query: '',
                departement: [],
                departements: [],
                responsables: [],
                selectedResponsable: null,
                chosenResponsable: '',

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
            eventBus.$on('addedProf', data => {
               //this.query = data.nom+" "+data.prenom;
               this.formEtu.responsableTel = data.telephone;
               this.formEtu.responsableMail = data.mail;
               this.$set(this.query,data.nom+" "+data.prenom);

                console.log(this.query);
            });
        },
        watch: {
            query: _.debounce(function(newQuery) {
                this.getResponsables(newQuery);
            },250)
        },
        methods: {
            next(){
                this.formulaire = false;
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

            selectResp($event) {
                this.$set(this.formEtu,'responsableTel',$event['telephone']);
                this.$set(this.formEtu,'responsableMail',$event['mail']);
                this.$set(this.formEtu,'responsabe_projet',$event['id']);

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