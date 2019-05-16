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
                                <b-button variant="outline-danger" class="ml-5" @click="createPDF">Immobilisation</b-button>
                            </b-col>
                        </b-row>

                        <b-row align-h="around" class="mt-4">
                            <b-col cols="6">

                                <b-card>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4">
                                            <label for="refReforme">Référence</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="refReforme">{{ reforme.reference }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="stockageReforme">Lieu de stockage</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="stockageReforme">{{ reforme.stockage }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="htReforme">Prix d'achat HT</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="htReforme">{{ reforme.prix_ht }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="ttcReforme">Prix d'achat TTC</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="ttcReforme">{{ reforme.prix_ttc }}</p>
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
                                            <label for="boncomReforme">Bon de commande</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="boncomReforme">{{ reforme.bon_commande }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="financementReforme">Financement</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="financementReforme">{{ reforme.financement }}</p>
                                        </b-col>
                                        <b-col sm="4">
                                            <label for="immobilisationReforme">Immobilisation</label>
                                        </b-col>
                                        <b-col sm="7">
                                            <p id="immobilisationReforme">{{ reforme.immobilisation }}</p>
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
    import jsPDF from "jspdf"
    import test from "../assets/Logo_universite_de_lorraine.png";

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
            },

            createPDF() {
                let imgData = 'data:image/png;base64,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';
                let date = new Date().toDateString();
                const doc = new jsPDF();
                //logo lorraine
                doc.addImage(imgData,'PNG',20,20,56,20);

                //titre du document
                doc.setFontSize(22);
                doc.text('Réforme de matériel',105,60,'center');

                doc.setFontSize(16);
                doc.text('Fiche Immobilisation n°'+this.reforme.immobilisation,20,80,'left');

                //encadré materiel
                let y = 100;
                doc.rect(20,y-7,170,10);
                doc.text('Materiel',105,y,'center');
                doc.text('Type:',30,y+13,'left');
                doc.text(this.materielType.text,90,y+13,'left');
                doc.text('Constructeur:',30,y+21,'left');
                doc.text(this.materiel.constructeur,90,y+21,'left');
                doc.text('Modèle:',30,y+29,'left');
                doc.text(this.materiel.modele,90,y+29,'left');
                doc.rect(20,y+3,170,31);


                //encadré exemplaire
                y = 150;
                doc.rect(20,y-7,170,10);
                doc.text('Exemplaire',105,y,'center');
                doc.text('Référence UL:',30,y+13,'left');
                doc.text(this.reforme.reference,90,y+13,'left');
                doc.text('Numéro de série:',30,y+21,'left');
                doc.text(this.reforme.num_serie,90,y+21,'left');
                doc.text('Prix HT:',30,y+29,'left');
                doc.text(this.reforme.prix_ht + ' €',90,y+29,'left');
                doc.text('Prix TTC:',30,y+37,'left');
                doc.text(this.reforme.prix_ttc + ' €',90,y+37,'left');
                doc.text('Bon de commande:',30,y+45,'left');
                doc.text(this.reforme.bon_commande,90,y+45,'left');
                doc.text('Financement:',30,y+53,'left');
                doc.text(this.reforme.financement,90,y+53,'left');
                doc.text('Date d\'achat:',30,y+61,'left');
                doc.text(this.reforme.date_achat,90,y+61,'left');
                doc.text('Date de sortie:',30,y+69,'left');
                doc.text(this.reforme.date_sortie,90,y+69,'left');
                doc.text('Fournisseur:',30,y+77,'left');
                doc.text(this.fournisseur.nom,90,y+77,'left');
                doc.rect(20,y+3,170,79);

                //encadré tampon
                y =240;
                doc.rect(110,y,80,40);

                //sauver et telecharger
                doc.save(this.reforme.date_sortie+'_ref-'+this.reforme.reference+'.pdf');


            }

        }
    }

</script>