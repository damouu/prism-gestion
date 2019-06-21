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


                        <div v-if="loading" class="loading text-center mt-5 mb-5">
                            <b-spinner label="loading" class="text-center"></b-spinner>
                            <h5>Chargement, veuillez patienter ... </h5>
                        </div>

                        <div v-else>


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

                        </div>

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
                loading:false,

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
                this.loading=true;
                axios.get('/exemplaires/'+this.reformeId+'?select=reforme')
                    .then( response => {
                        this.reforme = response.data.exemplaire;
                        this.fournisseur = response.data.exemplaire.fournisseur;
                        this.materiel = response.data.exemplaire.materiel;

                        this.materielType = { 'value': this.materiel.type.id, 'text': this.materiel.type.nom };
                        this.loading=false;
                    })
                    .catch( error => {
                        this.showAlert(error.response.statusText,error.response.status,error.response.data.message);

                    });
            },

            editReforme () {
                eventBus.$emit('editReforme', {'reforme': this.reforme});
            },

            createPDF() {
                let imgData = 'data:image/jpg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAC9BAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA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AFFFFABRRRQA2SKOaJ4pUV43BVkYZDA9QRXiXjr4Dw3by6h4TdLeU5ZrCQ4jY/7Dfw/Q8e4r2+igD4b1bRtS0K9az1SxntLhf4JkK59x6j3HFUa+5dT0jTtatDa6nY293Af4JowwHuM9D7ivNNb+AHhjUGaTTLi70yQ9FVvNjH4Nz/49VXEfMtFex3/7O2vxMfsGr6dcr280PET+ADD9ayH+A/jZDhYbF/dbkf1FFwPM6K9Ut/gB4xmYCWXTIB3Lzsf/AEFTXS6X+zl8ytq/iDjvHaQ/+zMf/ZaLgeDV3Xg74UeJPFzxzLbmw05uTd3KkAj/AGF6t/L3r6G8PfCzwj4aZJbXS0uLleRcXZ81wfUZ4B+gFdlSuM5bwZ4A0TwRZmPToTJdOuJruXBkk9vYew/XrXU0UUgCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArlPG/8ArPDP/Ydt/wD0F66usHxNpV1qj6IbZVItNUiupdzYwihgcep5FAG9RRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFeYfED4LaZ411KXV7e/msNTlCiRiPMjk2gAZUkEcADg49q9PooA+XLv9nfxhDKRb3Ol3CdmEzKfxBWrGmfs5eJbiVf7R1PTrOHPJjLSv8AlgD9a+m6KAOM8DfDPQPAkRexja4v3XbJeT4Lkeijoo9h+JNdnRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAH/9k=';
                const doc = new jsPDF();
                //logo lorraine
                doc.addImage(imgData,'PNG',20,20,105,20);

                //titre du document
                doc.setFontSize(22);
                doc.text('Réforme de matériel',105,60,'center');

                doc.setFontSize(16);
                if(this.reforme.immobilisation){
                    doc.text('Fiche Immobilisation n°'+this.reforme.immobilisation,20,80,'left');
                }else{
                    doc.text('Aucune fiche d\'immobilisation',20,80,'left');
                }


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
                if(!this.reforme.immobilisation){
                    doc.text('Référence PRISM:',30,y+13,'left');
                }else{
                    doc.text('Référence UL:',30,y+13,'left');
                }
                doc.text(this.reforme.reference,90,y+13,'left');
                doc.text('Numéro de série:',30,y+21,'left');
                doc.text(this.reforme.num_serie,90,y+21,'left');
                doc.text('Prix HT:',30,y+29,'left');
                doc.text(this.reforme.prix_ht + ' €',90,y+29,'left');
                doc.text('Prix TTC:',30,y+37,'left');
                doc.text(this.reforme.prix_ttc + ' €',90,y+37,'left');
                doc.text('Bon de commande:',30,y+45,'left');
                if(this.reforme.bon_commande){
                    doc.text(this.reforme.bon_commande,90,y+45,'left');
                }else{
                    doc.text('aucun',90,y+45,'left');
                }
                doc.text('Financement:',30,y+53,'left');
                if(this.reforme.financement){
                    doc.text(this.reforme.financement,90,y+53,'left');
                }else{
                    doc.text('aucun',90,y+53,'left');
                }
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