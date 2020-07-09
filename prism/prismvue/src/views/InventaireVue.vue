<template>
    <div id="inventaire">
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
                        <NavigationInventaire/>
                    </b-col>

                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Inventaire - En service</h1>
                            </b-col>
                            <b-col cols="7">
                                <b-button variant="success" class="mr-2 ml-2" v-b-modal.modal-AddCategorie>Ajouter une
                                    catégorie
                                </b-button>
                                <b-button variant="success" class="ml-2" v-b-modal.modal-AddMateriel
                                          @click="ModalAddMateriel">Ajouter un matériel
                                </b-button>
                            </b-col>
                        </b-row>

                        <div v-if="loading" class="loading text-center mt-5 mb-5">
                            <b-spinner label="loading" class="text-center"></b-spinner>
                            <h5>Chargement, veuillez patienter ... </h5>
                        </div>

                        <div v-else>
                            <b-tabs pills align="center">
                                <b-tab v-if='currentType == "Tous"' title="Tous" active>
                                </b-tab>
                                <b-tab @click="setCurrentType('Tous')" v-else title="Tous">
                                </b-tab>
                                <template v-for="type in types">
                                    <b-tab v-if='currentType == type.id' :title="type.nom" active>
                                    </b-tab>
                                    <b-tab v-else @click="setCurrentType(type.id)" :title="type.nom">
                                    </b-tab>
                                </template>
                            </b-tabs>
                            <br/>

                            <b-row>

                                <b-col md="5" class="m-auto">
                                    <b-form-group label-cols-sm="3" label="Filtre" class="mb-0">
                                        <b-input-group>
                                            <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                            <b-input-group-append>
                                                <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                </b-col>

                                <b-col md="3" class="m-auto">
                                    <b-form-group label-cols-sm="3" label="Par page">
                                        <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                    </b-form-group>
                                </b-col>

                                <b-table
                                        striped hover
                                        :items="fillMateriels"

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
                                        <h6 class="text-center">Pas de matériels à afficher.</h6>
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

            <ModalAddMateriel/>
            <ModalAddCategorie/>

        </div>
    </div>
</template>


<script>
    import {eventBus} from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalAddMateriel from '../components/modals/ModalAddMateriel';
    import ModalAddCategorie from '../components/modals/ModalAddCategorie';

    export default {
        name: 'InventaireVue',
        components: {
            NavigationInventaire,
            ModalAddMateriel,
            ModalAddCategorie,
        },
        data() {
            return {
                materiels: [],
                types: [],
                fillMateriels: [],
                currentType: 'Tous',
                mode: 'single',
                selected: [],
                sortBy: 'id',
                sortDesc: false,
                fieldsRow: [
                    {key: 'constructeur', sortable: true},
                    {key: 'modele', sortable: true},
                    {key: 'nb_ex', sortable: true},
                    {key: 'type', sortable: false},
                    {key: 'date_creation', sortable: true}
                ],
                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 30],
                filter: null,

                alert: {'show': false, 'showMateriel': false},
                dismissCountDown: 0,
                dismissSecs: 10,
                loading: false,
            }
        },
        computed: {
            rows() {
                return this.fillMateriels.length
            },
        },
        mounted() {
            // API -  GET ALL MATERIELS
            this.getAll();

            // API - GET TYPES
            this.getTypes();

            eventBus.$on('addedMateriel', data => {
                this.materiels = [];
                this.getAll();
            });
            eventBus.$on('error', data => {
                this.showAlert(data.error, data.status, data.message);
            });
            eventBus.$on('addedCategorie', data => {
                this.getAll();
                this.getTypes();
            });
        },
        methods: {
            getAll() {
                this.loading = true;
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/materiels/')
                    .then(response => {
                        this.materiels = response.data.materiels;
                        this.fillTable();
                        this.loading = false;
                    })
                    .catch(error => {
                        this.loading = false;
                        this.showAlert(error.response.statusText, error.response.status, error.response.data.message);
                    });
            },

            getTypes() {
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/types/')
                    .then(response => {
                        this.types = response.data.types;
                    })
                    .catch(function (error) {
                        this.showAlert(error.response.statusText, error.response.status, error.response.data.message);
                    });
            },

            setCurrentType(type) {
                this.currentType = type;
                this.fillTable();
            },

            fillTable() {
                this.fillMateriels = [];
                let listeMateriels = JSON.parse(JSON.stringify(this.materiels));
                listeMateriels.forEach(materiel => {
                    if (this.currentType == materiel.type.id || this.currentType == 'Tous') {
                        materiel.type = materiel.type.nom;
                        this.fillMateriels.push(materiel);
                    }
                });
            },

            rowSelected(items) {
                this.selected = items;
                let idSelected = items[0];
                this.$router.push({name: 'materiel', params: {id: idSelected.id}});
            },


            ModalAddMateriel() {
                eventBus.$emit('addMateriel', {'types': this.types});
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
            ModalDeleteCategorie() {
                eventBus.$emit('deleteCategorie');
            }

        }
    }

</script>