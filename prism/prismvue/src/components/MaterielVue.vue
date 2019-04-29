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
                            <b-row align-h="between">
                                <h3>Exemplaires : {{ exemplaires.length }}</h3>
                                <b-button variant="success" v-b-modal.modal-AddExemplaire>Ajouter un exemplaire</b-button>
                            </b-row>


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
                                @row-selected="rowSelected"

                                class="mt-4">
                            </b-table>
                        </div>
                        <div v-else>
                            <b-row align-h="around">
                                <b-button variant="outline-secondary" @click="backExemplaires">‹ retour</b-button>
                                <b-button variant="primary">Modifier</b-button>
                                <b-button variant="outline-danger">Supprimer</b-button>
                            </b-row>

                            <b-row class="mt-4">
                                <h4>Exemplaire</h4>
                            </b-row>

                            <b-row align-h="between" class="mt-2">
                                <b-col sm="4">
                                    <label for="idExemplaire">Id</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="idExemplaire">{{ selected.id }}</p>
                                </b-col>
                                <b-col sm="4">
                                    <label for="refExemplaire">Référence</label>
                                </b-col>
                                <b-col sm="7">
                                    <p id="refExemplaire">{{ selected.reference }}</p>
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
                                    <p id="siteWebFournisseur">{{ selectedFournisseur.site_web }}</p>
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
                                <b-form-select id="typeMatMod" v-model="materielTypeModif">
                                    <option v-for="option in types" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
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

                <b-modal
                id="modal-AddExemplaire"
                title="Ajout d'exemplaire"
                ref="modal"
                scrollable
                centered
                @ok="handleOkAddEx">
                <form ref="addExemplaire" @submit.stop.prevent="addExemplaire">
                    <b-form-group
                    label="Référence"
                    label-cols-sm="4"
                    label-align-sm="left"
                    label-for="addExemplaireRef">
                        <b-form-input id="addExemplaireRef" type="text" v-model="postExemplaire.reference" placeholder="Utilisez la douchette ou écrivez" required></b-form-input>
                    </b-form-group>
                    <b-form-group
                            label="Prix Achat"
                            label-cols-sm="4"
                            label-align-sm="left"
                            label-for="addExemplairePrix">
                        <b-form-input id="addExemplairePrix" type="number" step="any" v-model="postExemplaire.prix_achat" placeholder="Prix d'achat de l'exemplaire (en euros)" required></b-form-input>
                    </b-form-group>
                    <b-form-group
                            label="Numéro de Série"
                            label-cols-sm="4"
                            label-align-sm="left"
                            label-for="addExemplaireNumSerie">
                        <b-form-input id="addExemplaireNumSerie" type="text" v-model="postExemplaire.num_serie" placeholder="Numéro de série de l'exemplaire" required></b-form-input>
                    </b-form-group>
                    <b-form-group
                            label="URL Source"
                            label-cols-sm="4"
                            label-align-sm="left"
                            label-for="addExemplaireUrl">
                        <b-form-input id="addExemplaireUrl" type="text" v-model="postExemplaire.reference" placeholder="url source"></b-form-input>
                    </b-form-group>
                    <b-form-group
                            label="Date d'achat"
                            label-cols-sm="4"
                            label-align-sm="left"
                            label-for="addExemplaireDateAchat">
                        <b-form-input id="addExemplaireDateAchat" type="date" v-model="postExemplaire.date_achat" required></b-form-input>
                    </b-form-group>

                    <!-- METTRE ICI LISTE DEROULANTE FOURNISSEUR -->

                </form>

                </b-modal>

                <!-- PENSER FAIRE VERIFICATIONS REQUIRED INPUT -->

                <!-- FAIRE MODALE MODIF EXEMPLAIRE -->

                <!-- FAIRE MODALE SUPPRESSION EXEMPLAIRE -->

                <!-- FAIRE SUPPRESSION MATERIEL -->

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
                fields: [
                    { key: 'id', sortable:true },
                    { key: 'reference', sortable:true },
                    { key: 'num_ex', sortable:true },
                ],
                postExemplaire: false,
                items: false,
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();
            this.getExemplaires();

        },
        methods : {

            /*
             *
             *
             * RECUPERATION MATERIEL
             *
             *
             */

            getMateriel() {
                axios.get('/materiels/'+ this.materielId )
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.materielType = { 'value': response.data.materiel.type.id, 'text': response.data.materiel.type.nom };
                        this.materielTypeModif = JSON.parse(JSON.stringify(this.materielType.value));
                        this.materielModif = JSON.parse(JSON.stringify(this.materiel));
                    })
                    .catch( error => {
                        console.log(error)
                    });
            },

            /*
             *
             *
             * RECUPERATION TYPES
             *
             *
             */

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

            /*
             *
             *
             * RECUPERATION EXEMPLAIRES
             *
             *
             */

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


            /*
             *
             *
             * RECUPERATION LIGNE CLIQUEE TABLEAU
             *
             *
             */


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

            /*
             *
             *
             * BOUTONS RETOUR
             *
             *
             */

            backExemplaires() {
                this.selected = false;
                this.showEx=false;
            },

            backInventaire() {
                this.$router.push({path: '/inventaire'});
            },


            /*
             *
             *
             * MODAL MODIFICATION MATERIEL
             *
             *
             */


            handleOkModif(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.modifMateriel();
            },

            modifMateriel() {
                axios.put('/materiels/'+this.materielId,{
                        "constructeur": this.materielModif.constructeur,
                        "modele": this.materielModif.modele,
                        "type": this.materielTypeModif,
                        "nb_ex": this.materielModif.nb_ex,
                        "description": this.materielModif.description
                })
                    .then( response => {
                        this.materiel = response.data.materiel;
                        this.materielType = { 'value': this.materiel.type.id, 'text': this.materiel.type.nom };
                        this.$nextTick( () =>  {
                            this.$refs.modal.hide();
                        });
                    })
                    .catch( error => {
                        console.log(error.response);
                    })
            },


            /*
             *
             *
             * MODAL AJOUT EXEMPLAIRE
             *
             *
             */

            handleOkAddEx(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addExemplaire();
            },

            addExemplaire() {
                axios.post('/exemplaires',
                    {
                        "materiel": this.materielId,
                        "reference": this.postExemplaire.reference,
                        "fournisseur": this.postExemplaire.fournisseur,
                        "prix_achat": this.postExemplaire.num_serie,
                        "url": this.postExemplaire.url,
                        "etat": this.postExemplaire.etat,
                        "date_achat": this.postExemplaire.date_achat,
                    })
                    .then( response => {
                        this.getExemplaireId();
                    })
                    .catch( error => {
                        console.log(error);
                    })
            }

        }
    }

</script>