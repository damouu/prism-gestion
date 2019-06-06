<template>
    <div id="fournisseurs">
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
                                <h1 class="ml-5">Fournisseurs</h1>
                            </b-col>
                            <b-col cols="3">
                                <b-button variant="success" class="mr-2 ml-2" v-b-modal.modal-AddFournisseur>Ajouter un Fournisseur</b-button>
                            </b-col>
                        </b-row>

                        <b-table
                                 striped hover
                                 :items="fillFournisseurs"

                                 :fields="fieldsRow"
                                 :sort-by.sync="sortBy"
                                 :sort-desc.sync="sortDesc"

                                 show-empty

                                 selectable
                                 :select-mode="mode"
                                 :per-page="perPage"
                                 :current-page="currentPage"
                                 selectedVariant="success"
                                 @row-selected="rowSelected">

                            <template slot="empty" slot-scope="scope">
                                <h6 class="text-center">Pas de Fournisseurs à afficher.</h6>
                            </template>
                        </b-table>
                        <b-pagination
                                v-model="currentPage"
                                :total-rows="rows"
                                :per-page="perPage"
                        ></b-pagination>

                    </b-col>
                </b-row>
            </b-container>

            <ModalAddFournisseur />

        </div>
    </div>
</template>

<script>

    import { eventBus } from "../main";

    import NavigationInventaire from '../components/navigation/NavigationInventaire'
    import ModalAddFournisseur from "../components/modals/ModalAddFournisseur";

    export default {
        name: 'fournisseurs',
        components: {
            ModalAddFournisseur,
            NavigationInventaire,
        },
        data () {
            return {

                fournisseurs: [],
                fillFournisseurs: [],

                selected: [],
                mode: 'single',
                sortBy: 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'nom', sortable:true },
                    { key: 'adresse', sortable:true },
                    { key: 'ville', sortable:true },
                    { key: 'code_postal', sortable:true },
                    { key: 'site_web', sortable:true },
                    { key: 'mail', sortable:true },
                    { key: 'tel', sortable:true },
                ],

                currentPage: 1,
                perPage: 10,

                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,

            }
        },
        computed: {
            rows() {
                return this.fillFournisseurs.length
            }
        },
        mounted () {
            this.getFournisseurs();

            eventBus.$on('addedFournisseur', data => {
                this.fournisseurs = [];
                this.getFournisseurs();
            });

            eventBus.$on('error', data => {
                this.showAlert(data.error, data.status, data.message);
            });
        },
        methods : {

            rowSelected(items) {
                this.selected = items;
                let idSelected = items[0];
                this.$router.push({name: 'fournisseur', params: { id: idSelected.id }});
            },

            getFournisseurs() {
                axios.get('/fournisseurs')
                    .then(response => {
                        this.fournisseurs = response.data.fournisseurs;
                        this.fillTable();
                    })
                    .catch( error => {

                    })
            },

            fillTable() {
                this.fillFournisseurs = [];
                let listeFournisseurs = JSON.parse(JSON.stringify(this.fournisseurs));
                listeFournisseurs.forEach(fournisseur => {
                   this.fillFournisseurs.push(fournisseur);
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