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
                        <NavigationInventaire/>
                    </b-col>

                    <b-col cols="10">
                        <b-row>
                            <b-button variant="outline-secondary" @click="backFournisseurs">‹ retour</b-button>
                            <h1 class="ml-5">Fournisseur</h1>
                        </b-row>

                        <div v-if="loading" class="loading text-center mt-5 mb-5">
                            <b-spinner label="loading" class="text-center"></b-spinner>
                            <h5>Chargement, veuillez patienter ... </h5>
                        </div>
                        <div v-else>

                            <b-row align-h="between" class="mt-4">
                                <h3>Informations</h3>
                                <b-col cols="4">
                                    <b-button class="mrs-2" variant="primary" v-b-modal.modal-EditFournisseur
                                              @click="modalEditFournisseur">Modifier
                                    </b-button>
                                </b-col>
                            </b-row>

                            <b-row align-h="around" class="mt-4">
                                <b-col cols="6">

                                    <b-card>
                                        <b-row align-h="between" class="my-1">
                                            <b-col sm="4" class="mt-1">
                                                <label for="nomFournisseur">Nom</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="nomFournisseur">{{fournisseur.nom}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="adresseFournisseur">Adresse</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="adresseFournisseur">{{fournisseur.adresse}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="villeFournisseur">Ville</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="villeFournisseur">{{fournisseur.ville}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="codePostalFournisseur">Code postal</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="codePostalFournisseur">{{fournisseur.code_postal}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="mailFournisseur">mail</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="mailFournisseur">{{fournisseur.mail}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="telFournisseur">Téléphone</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="telFournisseur">{{fournisseur.tel}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="siteWebFournisseur">Site Web</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="siteWebFournisseur">{{fournisseur.site_web}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="dateCreationFournisseur">Date Création</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="dateCreationFournisseur">{{fournisseur.date_creation}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="dateModifFournisseur">Date Modification</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="dateModifFournisseur">{{fournisseur.date_modification}}</p>
                                            </b-col>
                                        </b-row>
                                    </b-card>

                                </b-col>

                                <b-col cols="6">
                                    <b-card>
                                        <b-card-title>Commercial</b-card-title>
                                        <b-row>
                                            <b-col sm="4" class="mt-1">
                                                <label for="nomComFournisseur">Nom</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="nomComFournisseur">{{fournisseur.commercial_nom}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="prenomComFournisseur">Prénom</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="prenomComFournisseur">{{fournisseur.commercial_prenom}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="telComFournisseur">Téléphone</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="telComFournisseur">{{fournisseur.commercial_tel}}</p>
                                            </b-col>
                                            <b-col sm="4" class="mt-1">
                                                <label for="mailComFournisseur">Mail</label>
                                            </b-col>
                                            <b-col sm="7" class="mt-1">
                                                <p id="mailComFournisseur">{{fournisseur.commercial_mail}}</p>
                                            </b-col>
                                        </b-row>

                                    </b-card>

                                </b-col>
                            </b-row>

                        </div>


                        <ModalEditFournisseur/>


                    </b-col>
                </b-row>
            </b-container>


        </div>
    </div>
</template>

<script>

    import {eventBus} from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalEditFournisseur from "../components/modals/ModalEditFournisseur";

    export default {
        name: "fournisseur",
        components: {
            NavigationInventaire,
            ModalEditFournisseur,
        },
        data() {
            return {
                dismissCountDown: 0,
                dismissSecs: 10,
                alert: {'show': false, 'showMateriel': false},

                fournisseurId: this.$route.params.id,
                fournisseur: [],
                loading: false,
            }
        },
        mounted() {
            this.getFournisseur();

            eventBus.$on('editedFournisseur', data => {
                this.fournisseur = [];
                this.getFournisseur();
            });

            eventBus.$on('error', data => {
                this.showAlert(data.error, data.status, data.message);
            });

        },
        methods: {

            backFournisseurs() {
                this.$router.push({path: '/inventaire/fournisseurs'});
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },
            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            getFournisseur() {
                this.loading = true;
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/fournisseurs/' + this.fournisseurId)
                    .then(response => {
                        this.fournisseur = response.data.fournisseur;
                        this.loading = false;
                    })
                    .catch(error => {
                        this.$router.push({path: '/notfound'});
                    })
            },

            modalEditFournisseur() {
                eventBus.$emit('editFournisseur', {'fournisseur': this.fournisseur})
            },

        }

    }
</script>