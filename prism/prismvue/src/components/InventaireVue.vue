<template>
    <div id="inventaire">
        <div>
            <b-container class="mt-5">
                <h1 class="ml-5">Inventaire - En service</h1>
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
                     :fixed="true"

                     :fields="fields"
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
            </b-container>

        </div>
    </div>
</template>



<script>

    export default {
        name: 'InventaireVue',
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
                fields: [
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
            }
        }
    }

</script>