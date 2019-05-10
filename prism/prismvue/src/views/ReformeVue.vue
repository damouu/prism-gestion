<template>
    <div id="reforme">
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
                            <b-button variant="outline-secondary" @click="backReformes">‹ retour</b-button>
                            <h1 class="ml-5">Exemplaire réformé</h1>
                        </b-row>


                        <b-row align-h="between" class="mt-4">
                            <h3>Informations</h3>
                            <b-col cols="8">
                                <b-button variant="primary" class="mr-5" v-b-modal.modal-EditReforme @click="editReforme">Modifier</b-button>
                                <b-button variant="outline-danger" class="ml-5" v-b-modal.modal-Immobilisation>Immobilisation</b-button>
                            </b-col>
                        </b-row>

                        <b-row align-h="around" class="mt-4">
                            <b-col cols="6">

                                <b-card>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4" class="mt-1">
                                            <label for="referenceReforme">Reference</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="referenceReforme">{{reforme.reference}}</p>
                                        </b-col>

                                        <b-col sm="4">
                                            <label for="stockageReforme">Lieu de stockage</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="stockageReforme">{{ reforme.stockage }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="prixReforme">Prix d'achat</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="prixReforme">{{ reforme.prix_achat }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="numSerieReforme">numéro de série</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="numSerieReforme">{{ reforme.num_serie }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="urlReforme">URL</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="urlReforme"><a v-bind:href="reforme.url" target="_blank">{{ reforme.url }}</a></p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="dateAchatReforme">Date d'achat</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="dateAchatReforme">{{ reforme.date_achat }}</p>
                                        </b-col>

                                        <b-col sm="4">
                                            <label for="dateSortieReforme">Date de sortie</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="dateSortieReforme">{{ reforme.date_sortie }}</p>
                                        </b-col>


                                    </b-row>
                                </b-card>
                            </b-col>
                            <b-col cols="6">

                                <b-card>
                                    <b-card-title>Materiel</b-card-title>
                                    <b-row align-h="between" class="my-1">
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
                                </b-card>
                            </b-col>
                            <b-col cols="6">



                                <b-card class="mt-4">
                                    <b-card-title>Fournisseur</b-card-title>
                                    <b-row align-h="between" class="mt-2">
                                        <b-col sm="4">
                                            <label for="nomFournisseur">Nom</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="nomFournisseur">{{ fournisseur.nom }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="adresseFournisseur">adresse</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="adresseFournisseur">{{ fournisseur.adresse }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="villeFournisseur">Ville</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="villeFournisseur">{{ fournisseur.ville }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="codePostalFournisseur">Code Postal</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="codePostalFournisseur">{{ fournisseur.code_postal }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="siteWebFournisseur">Site Web</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="siteWebFournisseur"><a v-bind:href="fournisseur.site_web" target="_blank">{{ fournisseur.site_web }}</a></p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="mailFournisseur">Mail</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="mailFournisseur">{{ fournisseur.mail }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="telFournisseur">Téléphone</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="telFournisseur">{{ fournisseur.tel }}</p>
                                        </b-col>

                                    </b-row>
                                </b-card>
                            </b-col>
                            <b-col cols="6">

                                <b-card class="mt-4" v-if="fournisseur.commercial_nom || fournisseur.commercial_prenom || fournisseur.commercial_tel || fournisseur.commercial_mail">
                                    <b-card-title>Commercial</b-card-title>
                                    <b-row align-h="between" class="mt-2">
                                        <b-col sm="4">
                                            <label for="comNomFournisseur">Commercial</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="comNomFournisseur">{{ fournisseur.commercial_nom }} {{ fournisseur.commercial_prenom }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="comTelFournisseur">Tel Commercial</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="comTelFournisseur">{{ fournisseur.commercial_tel }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="comMailFournisseur">Mail Commercial</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="comMailFournisseur">{{ fournisseur.commercial_mail }}</p>
                                        </b-col>
                                    </b-row>
                                </b-card>

                            </b-col>
                        </b-row>

                    </b-col>
                </b-row>
            </b-container>

            <ModalEditReforme />

        </div>
    </div>
</template>


<script>
    import { eventBus } from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalEditReforme from "../components/modals/ModalEditReforme";
    export default {
        name:"reforme",
        components: {
            ModalEditReforme,
            NavigationInventaire
        },
        data () {
            return {
                reformeId: this.$route.params.id,
                reforme: [],
                fournisseur: [],
                materiel: [],
                materielType: [],

                dismissCountDown:0,
                dismissSecs:10,
                alert: [],

            }
        },
        mounted () {
            this.getReformeId();

            eventBus.$on('editedReforme', data => {
                this.$router.push({path: '/inventaire/reformes'});
            });
        },
        methods: {

            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },

            backReformes() {
                this.$router.push({path: '/inventaire/reformes'});
            },

            getReformeId() {
                axios.get('/exemplaires/'+this.reformeId+'?select=reforme')
                    .then( response => {
                        this.reforme = response.data.exemplaire;
                        this.fournisseur = response.data.exemplaire.fournisseur;
                        this.materiel = response.data.exemplaire.materiel;

                        this.materielType = { 'value': this.materiel.type.id, 'text': this.materiel.type.nom };
                    })
                    .catch( error => {
                        console.log(error);
                    });
                console.log(this.reforme);
            },

            editReforme () {
                eventBus.$emit('editReforme', {'reforme': this.reforme});
            }


        }
    }

</script>