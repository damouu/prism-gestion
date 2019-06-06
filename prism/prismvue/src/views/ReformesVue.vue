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

                        <b-table
                                striped hover
                                 :items="fillReformes"

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
                                <h6 class="text-center">Pas d'exemplaires en réforme.</h6>
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

                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},
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
                axios.get('/exemplaires?select=reforme')
                    .then(response => {
                        this.reformes = response.data.reformes;
                        this.fillTable();
                    })
                    .catch( error => {
                        this.showAlert(error.response.statusText,error.response.status,error.response.data.message);

                    })
            },

            fillTable() {
                this.loaded = 'false';
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