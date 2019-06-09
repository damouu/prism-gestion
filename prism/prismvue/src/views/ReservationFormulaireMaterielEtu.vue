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
                                        <b-card-title>Dates d'emprunt</b-card-title>

                                        <div>
                                            Du:
                                            <b-row>
                                                <b-col cols="6">
                                                    <b-form-input type="date"></b-form-input>
                                                </b-col>
                                                <b-col cols="6">
                                                    <b-form-input type="time"></b-form-input>
                                                </b-col>
                                            </b-row>
                                            au:
                                            <b-row>
                                                <b-col cols="6">
                                                    <b-form-input type="date"></b-form-input>
                                                </b-col>
                                                <b-col cols="6">
                                                    <b-form-input type="time"></b-form-input>
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
                                                :items="exemplaires"
                                                striped hover
                                                :fields="fieldsMateriel"
                                                show-empty
                                                class="mt-4">

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

                resa: [],
            }
        },
        mounted() {
            console.log(this.formulaireId);
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

        }

    }
</script>