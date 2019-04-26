<template>
    <div id="materiel">
        <div>
            <b-container class="mt-5">
                <h1 class="ml-5">Materiel</h1>

                <b-row align-h="around" class="mt-3">
                    <b-col cols="5">

                        <b-row align-h="around">
                            <h3>Informations</h3>
                            <b-button variant="primary" v-b-modal.modal-Materiel>Modifier</b-button>
                        </b-row>

                        <b-row align-h="between" class="my-1 mt-4">
                            <b-col sm="4" class="mt-1">
                                <label for="idMat">Id</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="idMat">{{materiel.id}}</p>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="consMat">Constructeur</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="consMat">{{materiel.constructeur}}</p>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="modeleMat">Modele</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="ModeleMat">{{materiel.modele}}</p>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="typeMat">Type</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="typeMat">{{materielType.text}}</p>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="dateCreaMat">Date Creation</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="dateCreaMat">{{materiel.date_creation}}</p>
                            </b-col>

                            <b-col sm="4" class="mt-1">
                                <label for="descriptionMat">Description</label>
                            </b-col>
                            <b-col sm="7" class="mt-1">
                                <p id="descriptionMat">{{materiel.description}}</p>
                            </b-col>
                        </b-row>
                    </b-col>

                    <b-col cols="5">
                        <div  v-if="!showEx">
                            <h3>Exemplaires : {{ exemplaires.length }}</h3>

                            <b-table v-if="exemplaires.length > 0"
                                striped hover
                                :items="exemplaires"
                                :fixed="true"

                                :fields="fields"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"

                                selectable
                                :select-mode="mode"
                                selectedVariant="success"
                                @row-selected="rowSelected">
                            </b-table>
                        </div>
                        <div v-else>
                            <b-button variant="outline-primary" @click="backExemplaires">‹ retour</b-button>
                        </div>


                    </b-col>
                </b-row>

                <b-modal
                    id="modal-Materiel"
                    title="Modification de matériel"
                    ref="modal"
                    scrollable
                    centered
                    @ok="handleOkModif">
                    <form ref="modifMateriel" @submit.stop.prevent="modifMateriel">
                        <b-row class="my-1 mt-4">
                            <b-col sm="4" class="mt-1">
                                <label for="consMatMod">Constructeur</label>
                            </b-col>
                            <b-col sm="8" class="mt-1">
                                <b-form-input
                                        :type="type.text"
                                        v-model="materielModif.constructeur"
                                        id="consMatMod">
                                </b-form-input>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="modeleMatMod">Modele</label>
                            </b-col>
                            <b-col sm="8" class="mt-1">
                                <b-form-input
                                        :type="type.text"
                                        v-model="materielModif.modele"
                                        id="modeleMatMod">
                                </b-form-input>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="typeMatMod">Type</label>
                            </b-col>
                            <b-col sm="8" class="mt-1">
                                <b-form-select
                                        v-model="materielTypeModif.text"
                                        :options="types"
                                        id="typeMatMod">
                                </b-form-select>
                            </b-col>
                            <b-col sm="4" class="mt-1">
                                <label for="descriptionMatMod">Description</label>
                            </b-col>
                            <b-col sm="8" class="mt-1">
                                <b-form-textarea
                                        :type="type.text"
                                        rows="2"
                                        v-model="materielModif.description"
                                        id="descriptionMatMod">
                                </b-form-textarea>
                            </b-col>
                        </b-row>
                    </form>

                </b-modal>

            </b-container>

        </div>
    </div>
</template>



<script>

    export default {
        name: 'MaterielVue',
        data() {
            return {
                materielId: this.$route.params.id,
                materiel: [],
                materielModif: false,
                materielType: false,
                materielTypeModif: false,
                selectedValue: false,
                selected: false,
                showEx: false,
                types: [],
                type: { 'text' : 'text','date' : 'date'},
                rowsMat: [
                    'id',
                    'constructeur',
                    'modele',
                    'description',
                    'nombre d\'exemplaires',
                    'type',
                    'date Creation'
                ],
                exemplaires: [],
                mode: 'single',
                sortBy : 'id',
                sortDesc: false,
                fields: [
                    { key: 'id', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'num_ex', sortable:true },
                ]
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();
            this.getExemplaires();

        },
        methods : {

            getMateriel() {
                axios.get('/materiels/'+ this.materielId )
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.materielType = { 'value': response.data.materiel.id, 'text': response.data.materiel.type.nom };
                        this.materielTypeModif = JSON.parse(JSON.stringify(this.materielType));
                        this.materielModif = JSON.parse(JSON.stringify(this.materiel));
                    })
                    .catch( error => {
                        console.log(error)
                    });
            },


            getTypes() {
                axios.get('/types/' )
                    .then( response => {
                        for(let data of response.data.types) {
                            this.types.push({'value':data.id, 'text':data.nom});
                        }

                    })
                    .catch( error => {
                        console.log(error)
                    });
            },

            getExemplaires() {
                axios.get('/materiels/'+this.materielId+'/exemplaires')
                    .then( response => {
                        for(let data of response.data.materiels.exemplaire) {
                            this.exemplaires.push(data)
                        }
                    })
                    .catch( error => {
                        console.log(error);
                    })
            },

            rowSelected(items) {
                this.selected = items;
                this.showEx=true;
            },

            backExemplaires() {
                this.selected = false;
                this.showEx=false;
            },

            handleOkModif(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.modifMateriel();
            },

            modifMateriel() {
                console.log(this.materielModif.type);

                axios.put('/materiels/'+this.materielId,{
                        "constructeur": this.materielModif.constructeur,
                        "modele": this.materielModif.modele,
                        "type": this.materielTypeModif.value,
                        "nb_ex": this.materielModif.nb_ex,
                        "description": this.materielModif.description
                })
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.materielType = response.data.materiel.type.nom;

                        this.$nextTick( () =>  {
                            this.$refs.modal.hide();
                        });
                    })
                    .catch( error => {
                        console.log(error.response);
                    })



            }

        }
    }

</script>