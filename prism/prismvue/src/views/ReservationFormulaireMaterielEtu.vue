<template>
    <div id="ReservationFomulaireMaterielEtu">

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
                        <h1 class="ml-5">Reservation n°{{reservation}}</h1>
                    </b-row>

                    <b-card>
                        <b-card-title>Feuille reservation</b-card-title>
                        <form>

                            <b-row>
                                <b-col cols="6">
                                    <b-card class="mt-4">
                                        <b-row align-h="between">
                                            <b-card-title class="ml-2">Dates d'emprunt</b-card-title>
                                            <b-button @click="test" class="mr-2">Rechercher</b-button>
                                        </b-row>
                                        <div>
                                            Du:
                                                <b-form-group>
                                                    <b-row align-h="between">
                                                        <b-col cols="6">
                                                            <b-form-input
                                                                    id="formulaireDateEmprunt"
                                                                    v-validate="{required:true, regex:/^\d{4}-\d{2}-\d{2}$/}"
                                                                    type="date"
                                                                    data-vv-name="formulaireDateEmprunt"
                                                                    :state="validateState('formulaireDateEmprunt')"
                                                                    aria-describedby="invalidDateEmprunt"
                                                                    v-model="formulaire.date_emprunt"
                                                                    @click="test"
                                                            ></b-form-input>
                                                        </b-col>
                                                        <b-col cols="6">
                                                            <b-form-input
                                                                    id="formulaireHeureEmprunt"
                                                                    v-validate="{required:true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/}"
                                                                    type="time"
                                                                    data-vv-name="formulaireHeureEmprunt"
                                                                    :state="validateState('formulaireHeureEmprunt')"
                                                                    aria-describedby="invalidHeureEmprunt"
                                                                    v-model="formulaire.heure_emprunt"
                                                            ></b-form-input>
                                                        </b-col>

                                                    </b-row>

                                                    <b-form-invalid-feedback id="invalidDateEmprunt">Veuillez écrire une date valide.</b-form-invalid-feedback>
                                                    <b-form-invalid-feedback id="invalidHeureEmprunt">Veuillez écrire une heure valide.</b-form-invalid-feedback>
                                                </b-form-group>

                                            au:
                                            <b-row>
                                                <b-col cols="6">
                                                    <b-form-input
                                                            id="formulaireDateRetour"
                                                            type="date"
                                                            v-validate="{required:true, regex:/^\d{4}-\d{2}-\d{2}$/}"
                                                            data-vv-name="formulaireDateRetour"
                                                            :state="validateState('formulaireDateRetour')"
                                                            aria-describedby="invalidDateRetour"
                                                            v-model="formulaire.date_retour"
                                                    ></b-form-input>
                                                </b-col>
                                                <b-col cols="6">
                                                    <b-form-input
                                                            id="formulaireHeureRetour"
                                                            v-validate="{required:true, regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/}"
                                                            type="time"
                                                            data-vv-name="formulaireHeureRetour"
                                                            :state="validateState('formulaireHeureRetour')"
                                                            aria-describedby="invalidHeureRetour"
                                                            v-model="formulaire.heure_retour"
                                                    ></b-form-input>
                                                </b-col>
                                            </b-row>

                                        </div>



                                    </b-card>
                                    <b-card class="mt-4">
                                        <b-form-group label="Observations" label-for="observations">
                                            <b-textarea
                                                    id="observations"
                                                    v-model="resa.observations"
                                            ></b-textarea>
                                        </b-form-group>
                                    </b-card>
                                    <b-card class="mt-4">
                                        <b-card-title>Matériel à emprunter</b-card-title>

                                        <b-table
                                                :items="exemplaires"
                                                striped hover
                                                :fields="fieldsEmprunt"
                                                show-empty
                                                class="mt-4">

                                            <template slot="empty" slot-scope="scope">
                                                <h6 class="text-center">Pas d'exemplaires choisis.</h6>
                                            </template>

                                            <template slot="actions" slot-scope="row">
                                                <b-button size="sm" class="mr-1" variant="outline-danger" @click="deleteRow(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                            </template>

                                        </b-table>

                                    </b-card>
                                </b-col>
                                <b-col cols="6">
                                    <b-card class="mt-4">
                                        <b-card-title>Matériel disponible</b-card-title>
                                        <b-tabs pills align="center">
                                            <b-tab v-if='currentType == "Tous"' title="Tous" active>
                                            </b-tab>
                                            <b-tab @click="setCurrentType('Tous')" v-else title="Tous">
                                            </b-tab>
                                            <template v-for="type in types" >
                                                <b-tab v-if='currentType == type.id'  :title="type.nom" active>
                                                </b-tab>
                                                <b-tab v-else @click="setCurrentType(type.id)"  :title="type.nom">
                                                </b-tab>
                                            </template>
                                        </b-tabs>

                                        <b-table
                                                :items="sample"
                                                striped hover
                                                :fields="fieldsSample"
                                                show-empty
                                                class="mt-4">

                                            <template slot="detail" slot-scope="row">
                                                <b-button @click="row.toggleDetails" v-if="row.detailsShowing"><font-awesome-icon :icon="['fas','sort-down']" /></b-button>
                                                <b-button @click="row.toggleDetails" v-if="!row.detailsShowing"><font-awesome-icon :icon="['fas','caret-right']" /></b-button>
                                            </template>

                                            <template slot="row-details" slot-scope="row">
                                                <b-card>
                                                    <b-table
                                                            striped hover
                                                            :fields="fieldsExemple"
                                                            :items="row.item.type"
                                                            show-empty>
                                                        <template slot="empty" slot-scope="scope">
                                                            <h6 class="text-center">Pas d'exemplaires choisis.</h6>
                                                        </template>
                                                    </b-table>
                                                </b-card>

                                            </template>

                                            <template slot="empty" slot-scope="scope">
                                                <h6 class="text-center">Choisissez une date de sortie et une date de retour.</h6>
                                            </template>

                                            <template slot="actions" slot-scope="row">
                                                <b-button size="sm" class="mr-1" variant="outline-danger" @click="deleteRow(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                            </template>

                                        </b-table>
                                    </b-card>
                                </b-col>
                            </b-row>

                        </form>



                    </b-card>

                </b-col>
            </b-row>
        </b-container>

    </div>
</template>

<script>
    import NavigationReservationFeuille from "../components/navigation/NavigationReservationFeuille";
    export default {
        name: "ReservationFomulaireMaterielEtu",
        components:{
            NavigationReservationFeuille
        },
        data() {
            return {

                formulaireId: this.$route.params.id,
                formulaire: [],

                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},

                reservation: this.$route.params.id,

                currentType: "Tous",
                types: [],

                exemplaires: [],
                fieldsEmprunt: [
                    { key: 'materiel', sortable:true },
                    { key: 'type', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'actions', sortable:false },
                ],
                fieldsMateriel: [
                    { key: 'materiel', sortable:true },
                    { key: 'type', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'etat', sortable:true }
                ],

                fieldsSample: [
                    {key: 'detail',sortable:false},
                    {key:'constructeur', sortable:true},
                    {key:'modele', sortable:true},
                ],
                fieldsExemple: [
                    {key: 'id',sortable:true},
                    {key:'nom', sortable:false},
                ],

                resa: [],


                sample: [
                    { id:1, constructeur:"Canon", modele:"EOS 400", type: [{id:1,nom:"photo"},{id:2,nom:'test'}], date_creation:"2019-04-16"},
                    { id:1, constructeur:"SennHeiser", modele:"micro 500", type: {id:1,nom:"son"}, date_creation:"2019-04-16" }
                ]
            }
        },
        mounted() {
            this.getTypes();
            this.getFormulaire();
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

            deleteRow(items) {
                for ( const [key,value] of Object.entries(this.etudiants)){
                    if(value.id === items.id) {
                        this.etudiants.splice(key,1);
                        break;
                    }
                }
            },

            getFormulaire()
            {
                axios.get('/reservations/'+this.formulaireId)
                    .then( response =>{

                    })
                    .catch( error =>{
                        this.$router.push({path:'/notfound'});
                    });
            },


            getTypes() {

                axios.get('/types')
                    .then(response => {
                        this.types = response.data.types;
                    })
                    .catch(function (error) {
                        this.showAlert(error.response.statusText,error.response.status,error.response.data.message);
                    });
            },

            setCurrentType(type){
                this.currentType = type;
            },


            getExemplaires()
            {
                axios.get('/reservations')
            },


            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            selectedRow(row)
            {
                console.log(row);
                if(row.item.selected)
                    row.item.selected=false;
                else
                    row.item.selected=true;

                console.log(row);
            },

            test(){
                console.log(this.formulaire);
            }
        }

    }
</script>