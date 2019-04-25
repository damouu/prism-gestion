<template>
    <div id="materiel">
        <div>
            <b-container class="mt-5">
                <h1 class="ml-5">Materiel</h1>
                <b-row align-h="around" class="mt-3">
                    <b-col cols="5">
                        <h3>Informations</h3>
                        <b-row class="my-1">
                            <b-col sm="4">
                                <label for="idMat">Id</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-input
                                        :type="type.text"
                                        v-model="materiel.id"
                                        id="idMat"
                                        disabled >
                                </b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="consMat">Constructeur</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-input
                                        :type="type.text"
                                        v-model="materiel.constructeur"
                                        id="consMat"
                                        disabled >
                                </b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="modeleMat">Modele</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-input
                                        :type="type.text"
                                        v-model="materiel.modele"
                                        id="modeleMat"
                                        disabled >
                                </b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="typeMat">Type</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-select
                                        v-model="selected"
                                        :options="types"
                                        id="typeMat"
                                        disabled>
                                </b-form-select>
                            </b-col>
                            <b-col sm="4">
                                <label for="dateCreaMat">Date Creation</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-input
                                        :type="type.date"
                                        v-model="materiel.date_creation"
                                        id="dateCreaMat"
                                        >
                                </b-form-input>
                            </b-col>

                            <b-col sm="4">
                                <label for="descriptionMat">Description</label>
                            </b-col>
                            <b-col sm="8">
                                <b-form-textarea
                                        :type="type.text"
                                        rows="2"
                                        v-model="materiel.description"
                                        id="descriptionMat"
                                        disabled >
                                </b-form-textarea>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col cols="5">
                        <h3>Exemplaires</h3>

                        <b-table v-if="exemplaires.length > 0"
                            striped hover>

                        </b-table>

                    </b-col>
                </b-row>

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
                selected: false,
                types: [],
                type: { 'text' : 'text','date' : 'datetime'},
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
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();

            console.log(this.materiel);

        },
        methods : {

            getMateriel() {
                axios.get('/materiels/'+ this.materielId )
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.selected = response.data.materiel.type.nom;
                        })
                    .catch( error => {
                        console.log(error)
                    });
            },


            getTypes() {
                axios.get('/types/' )
                    .then( response => {
                        for(let data of response.data.types) {
                            this.types.push(data.nom)
                        }

                    })
                    .catch( error => {
                        console.log(error)
                    });
            }

        }
    }

</script>