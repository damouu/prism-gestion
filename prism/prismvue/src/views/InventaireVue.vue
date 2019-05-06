<template>
    <div id="inventaire">
        <div>
            <b-container fluid class="mt-5">

                <b-row class="mr-5 ml-5">

                    <b-col cols="2">
                        <NavigationInventaire />
                    </b-col>

                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Inventaire - En service</h1>
                            </b-col>
                            <b-col cols="5">
                                <b-button variant="success" class="mr-2" v-b-modal.modal-AddCategorie>Ajouter une catégorie</b-button>
                                <b-button variant="success" class="ml-2" v-b-modal.modal-AddMateriel @click="ModalAddMateriel">Ajouter un matériel</b-button>
                            </b-col>
                        </b-row>

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
                        <br/>
                        <b-table v-if="fillMateriels.length > 0"
                                 striped hover
                                 :items="fillMateriels"

                                 :fields="fieldsRow"
                                 :sort-by.sync="sortBy"
                                 :sort-desc.sync="sortDesc"

                                 selectable
                                 :select-mode="mode"
                                 selectedVariant="success"
                                 @row-selected="rowSelected">
                        </b-table>
                        <b-pagination
                                @click="pagination"
                                v-model="currentPage"
                                :total-rows="rows"
                        ></b-pagination>
                    </b-col>
                </b-row>
            </b-container>

            <ModalAddMateriel />
            <ModalAddCategorie />

        </div>
    </div>
</template>



<script>
    import { eventBus } from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalAddMateriel from '../components/modals/ModalAddMateriel';
    import ModalAddCategorie from '../components/modals/ModalAddCategorie';

    export default {
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
                currentType : 'Tous',
                mode: 'single',
                selected: [],
                sortBy : 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'id', sortable:true },
                    { key: 'constructeur', sortable:true },
                    { key: 'modele', sortable:true },
                    { key: 'nb_ex', sortable:true },
                    { key: 'type', sortable:false },
                    { key: 'date_creation', sortable:true }
                ],
                currentPage: 3,
                rows: 100
            }
        },
        mounted() {
            // API -  GET ALL MATERIELS
            this.getAll();

            // API - GET TYPES
            this.getTypes();

            eventBus.$on('addedCategorie', data => {
                this.types = [];
                this.getTypes();
            });
            eventBus.$on('addedMateriel', data => {
                this.materiels = [];
                this.getAll();
            });

        },
        methods : {
            getAll() {
                axios.get('/materiels')
                    .then(response => {
                        this.materiels = response.data.materiels;
                        this.fillTable();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            getTypes() {
                axios.get('/types')
                    .then(response => {
                        this.types = response.data.types;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            setCurrentType(type){
                this.currentType = type;
                this.fillTable();
            },

            fillTable(){
                this.fillMateriels = [];
                let listeMateriels = JSON.parse(JSON.stringify(this.materiels));
                listeMateriels.forEach(materiel => {
                    if(this.currentType == materiel.type.id || this.currentType == 'Tous'){
                        materiel.type = materiel.type.nom;
                        this.fillMateriels.push(materiel);
                    }
                });
            },

            rowSelected(items) {
                this.selected = items;
                let idSelected = items[0];
                this.$router.push({name: 'materiel', params: { id: idSelected.id }});
            },

            pagination(event) {
                console.log(change);
            },

            ModalAddMateriel() {
                eventBus.$emit('addMateriel', {'types': this.types});
            }
        }
    }

</script>