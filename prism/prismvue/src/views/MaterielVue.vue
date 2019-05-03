<template>
    <div id="materiel">
        <div>
            <b-container class="mt-5">

                <b-row>
                    <b-button variant="outline-secondary" @click="backInventaire">‹ retour</b-button>
                    <h1 class="ml-5">Materiel</h1>
                </b-row>


                <b-row align-h="around" class="mt-3">
                    <b-col cols="5">

                        <b-row align-h="around">
                            <h3>Informations</h3>
                            <b-button variant="primary" v-b-modal.modal-Materiel @click="modalEditMateriel">Modifier</b-button>
                        </b-row>

                        <b-row align-h="between" class="my-1 mt-4">
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
                            <ExemplairePart />
                    </b-col>
                </b-row>


                <!-- FAIRE SUPPRESSION MATERIEL -->

            </b-container>

        </div>
        <ModalEditMateriel />
        <ModalEditExemplaire />
        <ModalAddExemplaire />
        <ModalDeleteExemplaire />

    </div>
</template>



<script>

    import ModalEditMateriel from '../components/ModalEditMateriel';
    import ModalAddExemplaire from '../components/ModalAddExemplaire';
    import ModalEditExemplaire from '../components/ModalEditExemplaire';
    import ModalDeleteExemplaire from '../components/ModalDeleteExemplaire';
    import ExemplairePart from '../components/ExemplairePart';


    import { eventBus } from '../main';

    export default {
        name: 'MaterielVue',
        components: {
            ModalEditExemplaire,
            ModalAddExemplaire,
            ModalEditMateriel,
            ModalDeleteExemplaire,

            ExemplairePart,
        },
        data() {
            return {
                materielId: this.$route.params.id,
                materiel: [],
                materielType: [],

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
                selected: [],
                selectedFournisseur: [],
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();


            eventBus.$on('editedMateriel', data => {
                this.materiel = data.materiel;
                this.MaterielType = data.materielType;
            });


        },
        methods : {

            getMateriel() {
                axios.get('/materiels/'+ this.materielId )
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.materielType = { 'value': response.data.materiel.type.id, 'text': response.data.materiel.type.nom };
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



            backInventaire() {
                this.$router.push({path: '/inventaire'});
            },



            modalEditMateriel() {
                let data = {
                    'materielId': this.materielId,
                    'materiel': this.materiel,
                    'types': this.types,
                    'materielType': this.materielType,
                };
                eventBus.$emit('editMateriel', data);
            },


        }
    }

</script>