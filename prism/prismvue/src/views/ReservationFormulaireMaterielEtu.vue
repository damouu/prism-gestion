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
                                            <b-button @click="checkDate" class="mr-2">Rechercher</b-button>
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

                                        <b-row>
                                            <b-col cols="12" class="mt-3 mb-3">
                                                <b-form-group label-cols-sm="3" label="Filtre">
                                                    <b-input-group>
                                                        <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                                        <b-input-group-append>
                                                            <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                                        </b-input-group-append>
                                                    </b-input-group>
                                                </b-form-group>
                                            </b-col>
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
                                                <template slot="emptyfiltered" slot-scope="scope">
                                                    <h6 class="text-center">Aucune occurrence trouvée.</h6>
                                                </template>
                                                <template slot="agenda" slot-scope="row">
                                                    <b-button variant="success"><font-awesome-icon :icon="['fas','calendar-day']" /></b-button>
                                                </template>

                                                <template slot="row-details" slot-scope="row">
                                                    <b-card>
                                                        <b-table
                                                                striped hover
                                                                :fields="fieldsExemplaire"
                                                                :items="row.item.exemplaire"
                                                                show-empty selectable
                                                                :select-mode="mode"
                                                                @row-selected="rowSelected">
                                                            <template slot="empty" slot-scope="scope">
                                                                <h6 class="text-center">Pas d'exemplaires choisis.</h6>
                                                            </template>
                                                            <template slot="actions" slot-scope="row" class="text-center">
                                                                <b-button variant="warning" class="mr-1"><font-awesome-icon :icon="['fas','info-circle']" /></b-button>
                                                                <b-button variant="success" class="ml-1"><font-awesome-icon :icon="['fas','calendar-day']" /></b-button>
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
                                        </b-row>
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
                filter: null,
                mode: 'single',

                exemplaires: [],
                fieldsEmprunt: [
                    { key: 'materiel', sortable:true },
                    { key: 'type', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'actions', sortable:false, class:'m-auto'},
                ],

                fieldsMateriel: [
                    {key:'detail',sortable:false},
                    {key:'constructeur', sortable:true},
                    {key:'modele', sortable:true},
                    {key:'agenda', sortable:false},
                ],
                fieldsExemplaire: [
                    {key:'reference', sortable:true},
                    {key:'num_ex', sortable:true},
                    {key:'disponibilite', sortable:true},
                    {key:'actions', sortable:true},
                ],

                fillMateriels:[],
                resa: [],
                selected:[],


                sample: [
                    { id:1, constructeur:"Canon", modele:"EOS 400", type: [{id:1,nom:"photo"},{id:2,nom:'test'}], date_creation:"2019-04-16"},
                    { id:1, constructeur:"SennHeiser", modele:"micro 500", type: {id:1,nom:"son"}, date_creation:"2019-04-16" }
                ]
            }
        },
        mounted() {
            this.getTypes();
            this.getFormulaire();
            this.getAll();
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

                if(type === 'Tous')
                {
                    this.fillMateriels=JSON.parse(JSON.stringify(this.resa));
                }
                else{
                    this.fillMateriels = [];
                    this.resa.forEach( element => {
                        if(element.type.id === type)
                        {
                            this.fillMateriels.push(element);
                        }
                    });

                }


            },


            getAll()
            {
                axios.get('/materiels?select=reservation')
                    .then( response => {
                        this.resa = response.data.materiels;
                        this.fillMateriels = JSON.parse(JSON.stringify(this.resa));
                        let date_dep = new Date();
                        let date_ret = new Date(date_dep.getDate()+1);
                        this.fillMateriels.forEach( exem => {
                            exem.exemplaire.forEach( fiche => {
                                fiche.fiche_resa.forEach( el=> {
                                    if(el.date_depart>date_ret && el.date_retour<date_dep)
                                    {
                                        fiche.disponibilite = 'disponible';
                                    }
                                });
                                if(fiche.disponibilite!='disponible'){
                                    fiche.disponibilite='emprunté';
                                }
                            });
                        });

                    });
            },


            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            checkDate(){
                let dateNow = new Date();
                let date_depart = new Date(this.formulaire.date_emprunt+' '+this.formulaire.heure_emprunt);
                let date_retour = new Date(this.formulaire.date_retour+' '+this.formulaire.heure_retour);
                if((dateNow > date_depart) || (dateNow > date_retour))
                {
                    alert('nope');
                }
                else {
                    this.fillMateriels.forEach( mat => {
                        mat.exemplaire.forEach( exem => {
                            exem.disponibilite='disponible';
                            exem.fiche_resa.forEach( fiche => {
                                let date_dep = new Date(fiche.date_depart);
                                let date_ret = new Date(fiche.date_retour);
                                let dateNow = new Date();
                                if(dateNow >= date_ret){
                                    if((date_depart<date_ret) || (date_retour>date_dep))
                                    {
                                        exem.disponibilite = 'indisponible';
                                    }
                                }

                            });
                        })
                    });
                    console.log(this.fillMateriels);
                };
            },

            rowSelected(items) {
                this.selected = items;
                console.log(items);
            },
        }

    }
</script>