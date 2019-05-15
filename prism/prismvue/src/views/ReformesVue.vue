<template>
    <div id="reformes">
        <div>

            <b-container fluid class="mt-5">

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

                        <b-table v-if="fillReformes.length > 0"
                                 striped hover
                                 :items="fillReformes"

                                 :fields="fieldsRow"
                                 :sort-by.sync="sortBy"
                                 :sort-desc.sync="sortDesc"

                                 selectable
                                 :select-mode="mode"
                                 :per-page="perPage"
                                 :current-page="currentPage"
                                 selectedVariant="success"
                                 @row-selected="rowSelected">
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
                        console.log(error.response);
                    })
            },

            fillTable() {
                this.fillReformes = [];
                let listeReforme = JSON.parse(JSON.stringify(this.reformes));
                listeReforme.forEach(reforme => {
                    reforme.type = reforme.materiel.type.nom;
                    this.fillReformes.push(reforme);
                });
            }


        }
    }
</script>