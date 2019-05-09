<template>
    <div>
    <div v-show="!showEx">
        <b-row align-h="between">
            <h3>Exemplaires : {{ exemplaires.length }}</h3>
            <b-button variant="success" v-b-modal.modal-AddExemplaire @click="modalAddExemplaire">Ajouter un exemplaire</b-button>
        </b-row>
    </div>
        <div v-if="!showEx">
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
            <template slot="etat" slot-scope="row">
                <b-badge variant="success" v-if="row.value == 'disponible'" >{{row.value}}</b-badge>
                <b-badge variant="warning" v-if="row.value == 'réparation'">{{row.value}}</b-badge>
                <b-badge variant="danger" v-if="row.value == 'emprunté'">{{row.value}}</b-badge>
                <b-badge variant="secondary" v-if="row.value == 'non empruntable'">{{row.value}}</b-badge>
            </template>
        </b-table>


    </div>
    <div v-else="showEx">
        <b-row align-h="around">
            <b-button variant="outline-secondary" @click="backExemplaires">‹ retour</b-button>
            <b-button variant="primary" v-b-modal.modal-EditExemplaire @click="modalEditExemplaire">Modifier</b-button>
            <b-button variant="outline-danger" v-b-modal.modal-DelExemplaire @click="modalDeleteExemplaire">Supprimer</b-button>
        </b-row>

            <b-card  class="mt-4">
                <b-card-title>Exemplaire    <b-badge v-if="selected.etat === 'disponible'" variant="success">{{selected.etat}}</b-badge><b-badge v-if="selected.etat === 'emprunté'" variant="danger">{{selected.etat}}</b-badge><b-badge v-if="selected.etat === 'réparation'" variant="warning">{{selected.etat}}</b-badge><b-badge v-if="selected.etat === 'non empruntable'" variant="secondary">{{selected.etat}}</b-badge></b-card-title>
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
            </b-card>

            <b-card class="mt-4" v-if="selectedFournisseur.commercial_nom || selectedFournisseur.commercial_prenom || selectedFournisseur.commercial_tel || selectedFournisseur.commercial_mail">
                <b-card-title>Commercial</b-card-title>
                <b-row align-h="between" class="mt-2">
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
            </b-card>







    </div>

    </div>

</template>


<script>

    import { eventBus } from '../main';

    export default {
        name: 'ExemplairePart',
        data() {
            return {

                mode: 'single',
                sortBy : 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'reference', sortable:true },
                    { key: 'num_ex', sortable:true },
                    { key: 'etat', sortable:true },
                ],

                materielId: false,
                exemplaires: [],
                fournisseurs: [],


                selected: [],
                items: [],
                selectedFournisseur: [],
                showEx: false,

            }
        },
        mounted() {
            this.materielId = this.$route.params.id;
            this.getExemplaires();
            this.getFournisseurs();

            eventBus.$on('addedExemplaire', data => {
                    this.exemplaires = [];
                    this.getExemplaires();
            });
            eventBus.$on('editedExemplaire', data => {
                this.selected = data.exemplaire;
                this.getExemplaireId(data.exemplaire);
                this.exemplaires= [];
                this.getExemplaires();
            });
            eventBus.$on('deleteSuccessExemplaire', data => {
                this.backExemplaires();
                this.exemplaires = [];
                this.getExemplaires();
            });


        },
        methods: {

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
                this.getExemplaireId(items[0]);
                eventBus.$emit('selectRow', {'selected': this.selected, 'selectedFournisseur':this.selectedFournisseur});
                this.showEx = true;
            },


            getExemplaireId(data) {
                axios.get('/exemplaires/'+ data.id)
                    .then( response => {
                        this.selected = response.data.exemplaire;
                        this.selectedFournisseur = response.data.exemplaire.fournisseur;
                    })
                    .catch( error => {
                        console.log(error);
                    });
            },


            modalAddExemplaire() {
                eventBus.$emit('addExemplaire',
                    {
                        'fournisseurs':this.fournisseurs,
                        'materielId':this.materielId
                    })
            },

            backExemplaires() {
                this.selected = [];
                this.showEx= false;
            },

            modalEditExemplaire() {
                eventBus.$emit('editExemplaire',
                    {
                       'fournisseurs': this.fournisseurs,
                       'exemplaire': this.selected,
                       'fournisseurExemplaire': {'text':this.selectedFournisseur.nom, 'value': this.selectedFournisseur.id},
                    });
            },

            modalDeleteExemplaire() {
                eventBus.$emit('delExemplaire', {'exemplaire':this.selected});
            }





        }
    }

</script>