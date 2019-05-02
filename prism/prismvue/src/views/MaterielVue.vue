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
                        <div  v-if="!showEx">
                            <b-row align-h="between">
                                <h3>Exemplaires : {{ exemplaires.length }}</h3>
                                <b-button variant="success" v-b-modal.modal-AddExemplaire @click="modalAddExemplaire">Ajouter un exemplaire</b-button>
                            </b-row>


                            <b-table v-if="exemplaires.length > 0"
                                striped hover
                                :items="exemplaires"

                                :fields="fieldsRow"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"

                                selectable
                                :select-mode="mode"
                                selectedVariant="success"
                                @row-selected="rowSelected"

                                class="mt-4">
                            </b-table>
                        </div>
                        <div v-else>
                            <b-row align-h="around">
                                <b-button variant="outline-secondary" @click="backExemplaires">‹ retour</b-button>
                                <b-button variant="primary" v-b-modal.modal-EditExemplaire @click="modalEditExemplaire">Modifier</b-button>
                                <b-button variant="outline-danger">Supprimer</b-button>
                            </b-row>

                            <b-row class="mt-4">
                                <h4>Exemplaire    <b-badge v-if="selected.etat === 'disponible'" variant="success">{{selected.etat}}</b-badge><b-badge v-else variant="danger">{{selected.etat}}</b-badge></h4>
                            </b-row>

                            <b-row align-h="between" class="mt-2">
                                <b-col sm="4">
                                    <label for="refExemplaire">Référence</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="refExemplaire">{{ selected.reference }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="stockageExemplaire">Lieu de stockage</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="stockageExemplaire">{{ selected.stockage }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="prixExemplaire">Prix d'achat</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="prixExemplaire">{{ selected.prix_achat }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="numSerieExemplaire">numéro de série</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="numSerieExemplaire">{{ selected.num_serie }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="urlExemplaire">URL</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="urlExemplaire"><a v-bind:href="selected.url" target="_blank">{{ selected.url }}</a></p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="dateAchatExemplaire">Date d'achat</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="dateAchatExemplaire">{{ selected.date_achat }}</p>
                                </b-col>
                            </b-row>

                            <b-row class="mt-2">
                                <h4>Fournisseur</h4>
                            </b-row>

                            <b-row align-h="between" class="mt-2">
                                <b-col sm="4">
                                    <label for="nomFournisseur">Nom</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="nomFournisseur">{{ selectedFournisseur.nom }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="adresseFournisseur">adresse</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="adresseFournisseur">{{ selectedFournisseur.adresse }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="villeFournisseur">Ville</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="villeFournisseur">{{ selectedFournisseur.ville }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="codePostalFournisseur">Code Postal</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="codePostalFournisseur">{{ selectedFournisseur.code_postal }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="siteWebFournisseur">Site Web</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="siteWebFournisseur"><a v-bind:href="selectedFournisseur.site_web" target="_blank">{{ selectedFournisseur.site_web }}</a></p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="mailFournisseur">Mail</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="mailFournisseur">{{ selectedFournisseur.mail }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="telFournisseur">Téléphone</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="telFournisseur">{{ selectedFournisseur.tel }}</p>
                                </b-col>

                            </b-row>


                            <b-row align-h="between" class="mt-2" v-if="selectedFournisseur.commercial_nom || selectedFournisseur.commercial_prenom || selectedFournisseur.commercial_tel || selectedFournisseur.commercialmail">
                                <b-col sm="4">
                                    <label for="comNomFournisseur">Commercial</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="comNomFournisseur">{{ selectedFournisseur.commercial_nom }} {{ selectedFournisseur.commercial_prenom }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="comTelFournisseur">Tel Commercial</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="comTelFournisseur">{{ selectedFournisseur.commercial_tel }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="comMailFournisseur">Mail Commercial</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="comMailFournisseur">{{ selectedFournisseur.commercial_mail }}</p>
                                </b-col>
                            </b-row>
                        </div>
                    </b-col>
                </b-row>


                <ModalEditMateriel />
                <ModalEditExemplaire />
                <ModalAddExemplaire />

                <!-- FAIRE SUPPRESSION MATERIEL -->

            </b-container>

        </div>
    </div>
</template>



<script>

    import ModalEditMateriel from '../components/ModalEditMateriel';
    import ModalAddExemplaire from '../components/ModalAddExemplaire';
    import ModalEditExemplaire from '../components/ModalEditExemplaire';

    import { eventBus } from '../main';

    export default {
        name: 'MaterielVue',
        components: {
            ModalEditExemplaire,
            ModalAddExemplaire,
            ModalEditMateriel
        },
        data() {
            return {
                materielId: this.$route.params.id,
                materiel: [],
                materielType: [],
                selectedValue: false,
                selected: false,
                selectedFournisseur: false,
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
                fieldsRow: [
                    { key: 'id', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'num_ex', sortable:true },
                ],
                postExemplaire: [],
                modExemplaire: [],
                items: false,
                fournisseurs: [],
                modExemplaireFournisseur: [],
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();
            this.getExemplaires();
            this.getFournisseurs();

            eventBus.$on('editedMateriel', data => {
                this.materiel = data.materiel;
                this.MaterielType = data.materielType;
            });
            eventBus.$on('addedExemplaire', data => {
                this.exemplaires = [];
                this.getExemplaires();
            });
            eventBus.$on('editedExemplaire', data => {
                this.getExemplaireId();

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

            getFournisseurs() {
                axios.get('/fournisseurs')
                    .then( response => {
                        for(let data of response.data.fournisseurs) {
                            this.fournisseurs.push({'value':data.id, 'text': data.nom});
                        }
                    })
                    .catch( error => {
                        console.log(error);
                    });
            },


            rowSelected(items) {
                this.items = items;
                this.getExemplaireId();
            },

            getExemplaireId() {
                axios.get('/exemplaires/'+this.items[0].id)
                    .then( response => {
                        this.selected = response.data.exemplaire;
                        this.selectedFournisseur = response.data.exemplaire.fournisseur;
                        })
                    .catch( error => {
                            console.log(error);
                        });

                this.showEx=true;
            },


            backExemplaires() {
                this.selected = false;
                this.showEx=false;
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

            modalAddExemplaire() {
                let data = {
                    'materielId' : this.materielId,
                    'fournisseurs': this.fournisseurs,
                };
                eventBus.$emit('addExemplaire', data);
            },

            modalEditExemplaire() {
                let data = {
                    'fournisseurs': this.fournisseurs,
                    'exemplaire': this.selected,
                    'fournisseurExemplaire': {'value': this.selectedFournisseur.id, 'text': this.selectedFournisseur.nom },
                };
                eventBus.$emit('editExemplaire',data)

            },

            modalDeleteExemplaire() {

            }


        }
    }

</script>