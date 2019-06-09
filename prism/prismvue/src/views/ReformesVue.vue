<template>
    <div id="reformes">
        <div>

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
                        <NavigationInventaire />
                    </b-col>

                    <b-col cols="10">

                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Inventaire - Réformés</h1>
                            </b-col>
                        </b-row>

                        <div v-if="loading" class="loading text-center mt-5 mb-5">
                            <b-spinner label="loading" class="text-center"></b-spinner>
                            <h5>Chargement, veuillez patienter ... </h5>
                        </div>

                        <div v-else>

                            <b-row class="mt-4">

                                <b-col md="7">
                                    <b-form-group label-cols-sm="3" label="Filtre" class="mb-0">
                                        <b-input-group>
                                            <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                            <b-input-group-append>
                                                <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                </b-col>

                                <b-col md="5">
                                    <b-form-group label-cols-sm="4" label="Par page">
                                        <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                    </b-form-group>
                                </b-col>

                                <b-table
                                        striped hover
                                         :items="fillReformes"

                                         :fields="fieldsRow"
                                         :sort-by.sync="sortBy"
                                         :sort-desc.sync="sortDesc"
                                        :filter="filter"

                                         show-empty

                                         selectable
                                         :select-mode="mode"
                                         :per-page="perPage"
                                         :current-page="currentPage"
                                         selectedVariant="success"
                                         @row-selected="rowSelected">

                                    <template slot="empty" slot-scope="scope">
                                        <h6 class="text-center">Pas d'exemplaires en réforme.</h6>
                                    </template>

                                </b-table>
                                <b-pagination
                                        v-model="currentPage"
                                        :total-rows="rows"
                                        :per-page="perPage"
                                ></b-pagination>
                            </b-row>

                        </div>

                    </b-col>
                </b-row>
            </b-container>

        </div>
    </div>
</template>

<script>

    import { eventBus } from "../main";

    import NavigationInventaire from '../components/navigation/NavigationInventaire'

    export default {
        name: 'reformes',
        components: {
            NavigationInventaire,
        },
        data () {
            return {

                reformes: [],
                fillReformes: [],

                selected: [],
                mode: 'single',
                sortBy: 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'reference', sortable:true },
                    { key: 'type', sortable:true },
                    { key: 'num_serie', sortable:true },
                    { key: 'date_achat', sortable:true },
                    { key: 'date_sortie', sortable:true },
                ],

                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 30],
                filter: null,

                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},

                loading: false,
            }
        },
        computed: {
            rows() {
                return this.fillReformes.length
            }
        },
        mounted () {
            this.getReformes();


        },
        methods : {

            rowSelected(items) {
                this.selected = items;
                let idSelected = items[0];
                this.$router.push({name: 'reforme', params: { id: idSelected.id }});
            },

            getReformes() {
                this.loading=true;
                axios.get('/exemplaires?select=reforme')
                    .then(response => {
                        this.reformes = response.data.reformes;
                        this.fillTable();
                        this.loading=false;
                    })
                    .catch( error => {
                        this.showAlert(error.response.statusText,error.response.status,error.response.data.message);
                    })
            },

            fillTable() {
                this.fillReformes = [];
                let listeReforme = JSON.parse(JSON.stringify(this.reformes));
                listeReforme.forEach(reforme => {
                    reforme.type = reforme.materiel.type.nom;
                    this.fillReformes.push(reforme);
                });
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },
            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },


        }
    }
</script>