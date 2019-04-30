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
                                <b-button variant="primary">Modifier</b-button>
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

                <b-modal
                    id="modal-Materiel"
                    title="Modification de matériel"
                    ref="modal1"
                    scrollable
                    centered
                    size="lg"
                    @ok="handleOkModif">
                    <form ref="modifMateriel" @submit.stop.prevent="modifMateriel">
                        <b-form-group label="Constructeur *" label-for="consMatMod" label-cols-sm="4" label-align-sm="left">
                            <b-form-input
                                    id="consMatMod"
                                    name="materielModif.constructeur"
                                    v-model="materielModif.constructeur"
                                    v-validate="{required:true}"
                                    :state="validateState('materielModif.constructeur')"
                                    aria-describedby="invalidConstructeur"
                                    placeholder="Entrez le nom du constructeur"
                                    type="text" required>
                            </b-form-input>
                            <b-form-invalid-feedback id="invalidConstructeur">Veuillez entrer un nom de constructeur</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Modele *" label-for="modMatMod" label-cols-sm="4" label-align-sm="left">
                            <b-form-input
                                    id="modMatMod"
                                    name="materielModif.modele"
                                    v-model="materielModif.modele"
                                    v-validate="{required:true}"
                                    :state="validateState('materielModif.modele')"
                                    aria-describedby="invalidModele"
                                    placeholder="Entrez le nom du modèle"
                                    type="text" required>
                            </b-form-input>
                            <b-form-invalid-feedback id="invalidModele">Veuillez écrire un modèle</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group label="Type *" label-for="typeMatMod" label-cols-sm="4" label-align-sm="left">
                            <b-form-select
                                    id="typeMatMod"
                                    name="materielTypeModif"
                                    v-model="materielTypeModif"
                                    v-validate="{required:true}"
                                    :state="validateState('materielTypeModif')" required>
                                <option v-for="option in types" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </b-form-select>
                        </b-form-group>

                        <b-form-group label="Description" label-for="modMatMod" label-cols-sm="4" label-align-sm="left">
                            <b-form-textarea
                                    id="descriptionMatMod"
                                    name="materielModif.description"
                                    v-model="materielModif.description"
                                    placeholder="Ecrivez une description du matériel"
                                    rows="2">
                            </b-form-textarea>
                        </b-form-group>
                        <span class="text-danger">* champs obligatoires</span>

                    </form>
                </b-modal>

                <b-modal
                id="modal-AddExemplaire"
                title="Ajout d'exemplaire"
                ref="modal2"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddEx">
                <form ref="addExemplaire" @submit.stop.prevent="addExemplaire">
                    <b-form-group label="Référence *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireRef">
                        <b-form-input
                                id="addExemplaireRef"
                                name="postExemplaire.reference"
                                v-model="postExemplaire.reference"
                                v-validate="{required:true}"
                                :state="validateState('postExemplaire.reference')"
                                aria-describedby="invalidReference"
                                placeholder="Utilisez la douchette ou écrivez"
                                type="text" required>
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidReference">Veuillez écrire une référence ou scannez-la</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Prix Achat *" label-cols-sm="4" label-align-sm="left" label-for="addExemplairePrix">
                        <b-form-input
                                id="addExemplairePrix"
                                name="postExemplaire.prix_achat"
                                v-model="postExemplaire.prix_achat"
                                v-validate="{required:true, decimal:2}"
                                :state="validateState('postExemplaire.prix_achat')"
                                aria-describedby="invalidPrixAchat"
                                placeholder="Prix d'achat de l'exemplaire (en euros)"
                                type="text" required>
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidPrixAchat">Vous devez entrer une valeur numérique avec 2 décimales</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Numéro de Série *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireNumSerie">
                        <b-form-input
                                id="addExemplaireNumSerie"
                                name="postExemplaire.num_serie"
                                v-model="postExemplaire.num_serie"
                                v-validate="{required:true}"
                                :state="validateState('postExemplaire.num_serie')"
                                aria-describedby="invalidNumSerie"
                                placeholder="Numéro de série de l'exemplaire"
                                type="text" required>
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidNumSerie">Vous devez entrer un numéro de série</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Lieu de stockage *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireStockage">
                        <b-form-input
                                id="addExemplaireStockage"
                                name="postExemplaire.stockage"
                                v-model="postExemplaire.stockage"
                                v-validate="{required:true}"
                                :state="validateState('postExemplaire.stockage')"
                                aria-describedby="invalidStockage"
                                placeholder="Lieu de stockage de l'exemplaire"
                                type="text" required>
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidStockage">Vous devez entrer un lieu de stockage</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="URL Source" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireUrl">
                        <b-form-input
                                id="addExemplaireUrl"
                                name="postExemplaire.url"
                                v-model="postExemplaire.url"
                                v-validate="{required:false, url:{require_protocol: true}}"
                                :state="validateState('postExemplaire.url')"
                                aria-describedby="invalidUrl"
                                placeholder="url source"
                                type="text">
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidUrl">Vous devez entrer un URL valide</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Date d'achat *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireDateAchat">
                        <b-form-input
                                id="addExemplaireDateAchat"
                                name="postExemplaire.date_achat"
                                v-model="postExemplaire.date_achat"
                                v-validate="'required:true|date_format:yyyy-MM-dd'"
                                :state="validateState('postExemplaire.date_achat')"
                                aria-describedby="invalidDateAchat"
                                type="date" required>
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidDateAchat">Vous devez entrer une date au format DD/MM/AAAA valide.</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Etat" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireEtat">
                        <b-form-select
                                id="addExemplaireEtat"
                                name="postExemplaire.etat"
                                v-model="postExemplaire.etat"
                                v-validate="{required:true}"
                                :state="validateState('postExemplaire.etat')"
                                aria-describedby="invalidEtat"
                                required>
                            <option>disponible</option>
                            <option>réparation</option>
                            <option>emprunté</option>
                            <option>non empruntable</option>
                        </b-form-select>
                        <b-form-invalid-feedback id="invalidEtat">Vous devez choisir une option d'état.</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Fournisseur" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireFournisseur">
                        <b-form-select
                                id="addExemplaireFournisseur"
                                name="postExemplaire.fournisseur"
                                v-model="postExemplaire.fournisseur"
                                v-validate="{required:true}"
                                :state="validateState('postExemplaire.fournisseur')"
                                aria-describedby="invalidFournisseur"
                                required>
                            <option v-for="option in fournisseurs" v-bind:value="option.value">
                                {{ option.text }}
                            </option>
                        </b-form-select>
                        <b-form-invalid-feedback id="invalidFournisseur">Vous devez choisir un fournisseur.</b-form-invalid-feedback>
                    </b-form-group>

                </form>

                </b-modal>

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
                materielModif: [],
                materielType: [],
                materielTypeModif: [],
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
                items: false,
                fournisseurs: [],
            }
        },
        mounted() {

            this.getMateriel();
            this.getTypes();
            this.getExemplaires();
            this.getFournisseurs();

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
             * RECUPERATION Fournisseurs
             *
             *
             */

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





            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
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
                this.$validator.validateAll().then( (result) => {
                    if(!result)
                        return;
                    else {
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
                                this.$refs.modal1.hide();
                            });
                            this.$bvToast.toast(`Matériel modifié avec succès !`, {
                                title: `Modification réussie`,
                                toaster: 'b-toaster-top-center',
                                solid:true,
                                variant:'success',
                                appendToast: false
                            });
                        })
                        .catch( error => {
                            console.log(error.response);
                        });
                    }
                });

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
                this.$validator.validateAll().then( (result) => {
                    if (!result)
                        return;
                    else {
                        axios.post('/exemplaires',
                            {
                                "materiel": this.materielId,
                                "reference": this.postExemplaire.reference,
                                "fournisseur": this.postExemplaire.fournisseur,
                                "prix_achat": this.postExemplaire.prix_achat,
                                "num_serie": this.postExemplaire.num_serie,
                                "stockage": this.postExemplaire.stockage,
                                "url": this.postExemplaire.url,
                                "etat": this.postExemplaire.etat,
                                "date_achat": this.postExemplaire.date_achat,
                            })
                            .then(response => {
                                this.exemplaires = [];
                                this.getExemplaires();
                                this.$nextTick(() => {
                                    this.$refs.modal2.hide();
                                });
                                this.$bvToast.toast(`Exemplaire ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-top-center',
                                    variant:'success',
                                    solid:true,
                                    appendToast: false
                                });
                            })
                            .catch(error => {
                                console.log(error);
                            })
                    }
                });
            }



        }
    }

</script>