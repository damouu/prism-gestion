<template>
    <div id="materiel">
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

                            <b-row>
                                <b-button variant="outline-secondary" @click="backInventaire">‹ retour</b-button>
                                <h1 class="ml-5">Materiel</h1>
                            </b-row>

                            <b-row class="mt-4">
                                <b-col cols="6">
                                    <b-row align-h="around">
                                        <h3>Informations</h3>
                                        <b-button variant="primary" v-b-modal.modal-Materiel @click="modalEditMateriel">Modifier</b-button>
                                        <b-button variant="outline-danger" v-b-modal.modal-DeleteMateriel @click="modalDeleteMateriel">Supprimer matériel</b-button>
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

                                <b-col cols="6">
                                    <ExemplairePart />
                                </b-col>
                            </b-row>
                        </b-col>
                    </b-row>

            </b-container>

        </div>

        <ModalEditMateriel />
        <ModalEditExemplaire />
        <ModalAddExemplaire />
        <ModalDeleteExemplaire />
        <ModalDeleteMateriel />

    </div>
</template>



<script>
    import { eventBus } from '../main';

    import ModalEditMateriel from '../components/modals/ModalEditMateriel';
    import ModalAddExemplaire from '../components/modals/ModalAddExemplaire';
    import ModalEditExemplaire from '../components/modals/ModalEditExemplaire';
    import ModalDeleteExemplaire from '../components/modals/ModalDeleteExemplaire';
    import ModalDeleteMateriel from '../components/modals/ModalDeleteMateriel';
    import ExemplairePart from '../components/ExemplairePart';
    import NavigationInventaire from "../components/navigation/NavigationInventaire";

    export default {
        name: 'MaterielVue',
        components: {
            NavigationInventaire,
            ModalEditExemplaire,
            ModalAddExemplaire,
            ModalEditMateriel,
            ModalDeleteExemplaire,
            ModalDeleteMateriel,

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
                dismissCountDown:0,
                dismissSecs:10,
                alert: {'show':false,'showMateriel':false},
                selected: [],
                selectedFournisseur: [],
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();

            eventBus.$on('editedMateriel', data => {
                this.materiel = [];
                this.MaterielType = [];
                this.getMateriel();
            });
            eventBus.$on('deletedMateriel', data => {
                this.backInventaire();
            });
            eventBus.$on('deleteError', data => {
                this.showAlert(data.error, data.status, data.message);
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

            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },

            modalDeleteMateriel(){
                eventBus.$emit('deleteMateriel', {'id':this.materielId});
            }


        }
    }

</script>