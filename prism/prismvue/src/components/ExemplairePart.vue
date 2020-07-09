<template>
    <div>

        <div v-if="loading" class="loading text-center mt-5 mb-5">
            <b-spinner label="loading" class="text-center"></b-spinner>
            <h5>Chargement, veuillez patienter ... </h5>
        </div>
        <div v-else>

            <div v-show="!showEx">
                <b-row align-h="between">
                    <h3>Exemplaires : {{ exemplaires.length }}</h3>
                    <b-button variant="success" v-b-modal.modal-AddExemplaire @click="modalAddExemplaire">Ajouter un
                        exemplaire
                    </b-button>
                </b-row>
            </div>

            <div v-if="!showEx">

                <b-row class="mt-4">

                    <b-col md="7">
                        <b-form-group label-cols-sm="3" label="Filtre" class="mb-0">
                            <b-input-group>
                                <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                <b-input-group-append>
                                    <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>

                    <b-col md="5">
                        <b-form-group label-cols-sm="4" label="Par page">
                            <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                        </b-form-group>
                    </b-col>

                    <b-table
                            striped hover
                            :items="exemplaires"

                            :fields="fieldsRow"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            :filter="filter"

                            show-empty

                            selectable
                            :select-mode="mode"
                            :per-page="perPage"
                            :current-page="currentPage"
                            selectedVariant="success"
                            @row-selected="rowSelected">
                        <template slot="etat" slot-scope="row">
                            <b-badge variant="success" v-if="row.value == 'disponible'">{{row.value}}</b-badge>
                            <b-badge variant="warning" v-if="row.value == 'réparation'">{{row.value}}</b-badge>
                            <b-badge variant="danger" v-if="row.value == 'emprunté'">{{row.value}}</b-badge>
                            <b-badge variant="secondary" v-if="row.value == 'non empruntable'">{{row.value}}</b-badge>
                        </template>
                        <template slot="empty" slot-scope="scope">
                            <h6 class="text-center">Pas encore d'exemplaires pour ce matériel.</h6>
                        </template>
                    </b-table>
                    <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                    ></b-pagination>

                </b-row>

            </div>

            <div v-else="showEx">
                <b-row align-h="around">
                    <b-button variant="outline-secondary" @click="backExemplaires">‹ retour</b-button>
                    <b-button variant="primary" v-b-modal.modal-EditExemplaire @click="modalEditExemplaire">Modifier
                    </b-button>
                    <b-button variant="outline-danger" v-b-modal.modal-DelExemplaire @click="modalDeleteExemplaire">
                        Supprimer
                    </b-button>
                </b-row>

                <div v-if="loadingRow" class="loading text-center mt-5 mb-5">
                    <b-spinner label="loadingRow" class="text-center"></b-spinner>
                    <h5>Chargement, veuillez patienter ... </h5>
                </div>
                <div v-else="!loadingRow">

                    <b-card class="mt-4">
                        <b-card-title>Exemplaire
                            <b-badge v-if="selected.etat === 'disponible'" variant="success">{{selected.etat}}</b-badge>
                            <b-badge v-if="selected.etat === 'emprunté'" variant="danger">{{selected.etat}}</b-badge>
                            <b-badge v-if="selected.etat === 'réparation'" variant="warning">{{selected.etat}}</b-badge>
                            <b-badge v-if="selected.etat === 'non empruntable'" variant="secondary">{{selected.etat}}
                            </b-badge>
                        </b-card-title>
                        <b-row align-h="between">
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
                                <label for="htExemplaire">Prix d'achat HT</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="htExemplaire">{{ selected.prix_ht }}</p>
                            </b-col>
                            <b-col sm="4">
                                <label for="ttcExemplaire">Prix d'achat TTC</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="ttcExemplaire">{{ selected.prix_ttc }}</p>
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
                                <p id="urlExemplaire"><a v-bind:href="selected.url" target="_blank">{{ selected.url
                                    }}</a></p>
                            </b-col>
                            <b-col sm="4">
                                <label for="boncomExemplaire">Bon de commande</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="boncomExemplaire">{{ selected.bon_commande }}</p>
                            </b-col>
                            <b-col sm="4">
                                <label for="financementExemplaire">Financement</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="financementExemplaire">{{ selected.financement }}</p>
                            </b-col>
                            <b-col sm="4">
                                <label for="immobilisationExemplaire">Immobilisation</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="immobilisationExemplaire">{{ selected.immobilisation }}</p>
                            </b-col>

                            <b-col sm="4">
                                <label for="dateAchatExemplaire">Date d'achat</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="dateAchatExemplaire">{{ selected.date_achat }}</p>
                            </b-col>
                        </b-row>
                    </b-card>

                    <b-card class="mt-4">
                        <b-card-title>Fournisseur</b-card-title>
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
                                <p id="siteWebFournisseur"><a v-bind:href="selectedFournisseur.site_web"
                                                              target="_blank">{{ selectedFournisseur.site_web }}</a></p>
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
                    </b-card>

                    <b-card class="mt-4"
                            v-if="selectedFournisseur.commercial_nom || selectedFournisseur.commercial_prenom || selectedFournisseur.commercial_tel || selectedFournisseur.commercial_mail">
                        <b-card-title>Commercial</b-card-title>
                        <b-row align-h="between" class="mt-2">
                            <b-col sm="4">
                                <label for="comNomFournisseur">Commercial</label>
                            </b-col>
                            <b-col sm="7">
                                <p id="comNomFournisseur">{{ selectedFournisseur.commercial_nom }} {{
                                    selectedFournisseur.commercial_prenom }}</p>
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
                    </b-card>

                </div>

            </div>


        </div>


    </div>

</template>


<script>

    import {eventBus} from '../main';

    export default {
        name: 'ExemplairePart',
        data() {
            return {

                mode: 'single',
                sortBy: 'id',
                sortDesc: false,
                fieldsRow: [
                    {key: 'reference', sortable: true},
                    {key: 'num_ex', sortable: true},
                    {key: 'etat', sortable: true},
                ],
                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 30],
                filter: null,

                materielId: false,
                exemplaires: [],
                fournisseurs: [],


                selected: [],
                items: [],
                selectedFournisseur: [],
                showEx: false,
                loading: false,
                loadingRow: false,

            }
        },
        computed: {
            rows() {
                return this.exemplaires.length
            }
        },
        mounted() {

            this.materielId = this.$route.params.id;
            this.getExemplaires();
            this.getFournisseurs();

            eventBus.$on('addedExemplaire', data => {
                this.exemplaires = [];
                this.fournisseurs = [];
                this.getExemplaires();
                this.getFournisseurs();
            });
            eventBus.$on('editedExemplaire', data => {
                this.selected = data.exemplaire;
                this.getExemplaireId(data.exemplaire);
            });
            eventBus.$on('deleteSuccessExemplaire', data => {
                this.backExemplaires();
                this.exemplaires = [];
                this.fournisseurs = [];
                this.getExemplaires();
                this.getFournisseurs();
            });


        },
        methods: {

            getExemplaires() {
                this.loading = true;
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/materiels/' + this.materielId + '/exemplaires')
                    .then(response => {
                        for (let data of response.data.materiels.exemplaire) {
                            this.exemplaires.push(data)
                        }
                    })
                    .catch(error => {
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    })
            },
            getFournisseurs() {
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/fournisseurs/')
                    .then(response => {
                        for (let data of response.data.fournisseurs) {
                            this.fournisseurs.push({'value': data.id, 'text': data.nom});
                        }
                        this.loading = false;
                    })
                    .catch(error => {
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    });
            },

            rowSelected(items) {
                this.getExemplaireId(items[0]);
                eventBus.$emit('selectRow', {
                    'selected': this.selected,
                    'selectedFournisseur': this.selectedFournisseur
                });
                this.showEx = true;
            },


            getExemplaireId(data) {
                this.loadingRow = true;
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/exemplaires/' + data.id)
                    .then(response => {
                        this.selected = response.data.exemplaire;
                        this.selectedFournisseur = response.data.exemplaire.fournisseur;
                        this.loadingRow = false;
                    })
                    .catch(error => {
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    });
            },


            modalAddExemplaire() {
                eventBus.$emit('addExemplaire',
                    {
                        'fournisseurs': this.fournisseurs,
                        'materielId': this.materielId
                    })
            },

            backExemplaires() {
                this.selected = [];
                this.showEx = false;
            },

            modalEditExemplaire() {
                eventBus.$emit('editExemplaire',
                    {
                        'fournisseurs': this.fournisseurs,
                        'exemplaire': this.selected,
                        'fournisseurExemplaire': {
                            'text': this.selectedFournisseur.nom,
                            'value': this.selectedFournisseur.id
                        },
                    });
            },

            modalDeleteExemplaire() {
                eventBus.$emit('delExemplaire', {'exemplaire': this.selected});
            }
        }
    }

</script>