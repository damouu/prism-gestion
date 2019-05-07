<template>
    <div id="modalAddFournisseur">

        <b-modal
                id="modal-AddFournisseur"
                title="Ajout de Fournisseur"
                ref="modal9"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddFour">
            <form ref="addFournisseur" @submit.stop.prevent="addFournisseur">

                <b-form-group label="Nom *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurNom">
                    <b-form-input
                            id="addFournisseurNom"
                            data-vv-name="addFourNom"
                            v-model="fournisseur.nom"
                            v-validate="{required:true}"
                            :state="validateState('addFourNom')"
                            aria-describedby="invalidNom"
                            placeholder="Nom du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNom">Veuillez écrire un nom de fournisseur</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Adresse *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurAdresse">
                    <b-form-input
                            id="addFournisseurAdresse"
                            data-vv-name="addFourAdresse"
                            v-model="fournisseur.adresse"
                            v-validate="{required:true}"
                            :state="validateState('addFourAdresse')"
                            aria-describedby="invalidAdresse"
                            placeholder="Adresse Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidAdresse">Veuillez écrire une adresse</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Ville *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurVille">
                    <b-form-input
                            id="addFournisseurVille"
                            data-vv-name="addFourVille"
                            v-model="fournisseur.ville"
                            v-validate="{required:true}"
                            :state="validateState('addFourVille')"
                            aria-describedby="invalidVille"
                            placeholder="Ville Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidVille">Veuillez écrire une ville</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Code postal *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurCodePostal">
                    <b-form-input
                            id="addFournisseurCodePostal"
                            data-vv-name="addFourCodePostal"
                            v-model="fournisseur.code_postal"
                            v-validate="{required:true}"
                            :state="validateState('addFourCodePostal')"
                            aria-describedby="invalidCodePostal"
                            placeholder="Code postal Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCodePostal">Veuillez écrire un code postal</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurMail">
                    <b-form-input
                            id="addFournisseurMail"
                            data-vv-name="addFourMail"
                            v-model="fournisseur.mail"
                            v-validate="{required:true, email:true}"
                            :state="validateState('addFourMail')"
                            aria-describedby="invalidMail"
                            placeholder="adresse mail du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidMail">Veuillez écrire une adresse mail correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Telephone *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurTel">
                    <b-form-input
                            id="addFournisseurTel"
                            data-vv-name="addFourTel"
                            v-model="fournisseur.tel"
                            v-validate="{required:true}"
                            :state="validateState('addFourTel')"
                            aria-describedby="invalidTel"
                            placeholder="Numéro de téléphone du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidTel">Veuillez écrire un numéro de téléphone correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Site Web" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurSiteWeb">
                    <b-form-input
                            id="addFournisseurSiteWeb"
                            data-vv-name="addFourSiteWeb"
                            v-model="fournisseur.site_web"
                            v-validate="{required:false, url:{require_protocol: true}}"
                            :state="validateState('addFourSiteWeb')"
                            aria-describedby="invalidSiteWeb"
                            placeholder="site web du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidSiteWeb">Veuillez écrire un site web correct</b-form-invalid-feedback>
                </b-form-group>


                <b-form-group label="Nom Commercial" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurNomCom">
                    <b-form-input
                            id="addFournisseurNomCom"
                            data-vv-name="addFourNomCom"
                            v-model="fournisseur.commercial_nom"
                            v-validate="{required:false}"
                            :state="validateState('addFourNomCom')"
                            aria-describedby="invalidNomCom"
                            placeholder="Nom du commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNomCom">Veuillez écrire un nom correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prénom Commercial" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurPrenomCom">
                    <b-form-input
                            id="addFournisseurPrenomCom"
                            data-vv-name="addFourPrenomCom"
                            v-model="fournisseur.commercial_prenom"
                            v-validate="{required:false}"
                            :state="validateState('addFourPrenomCom')"
                            aria-describedby="invalidPrenomCom"
                            placeholder="Prénom du commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidPrenomCom">Veuillez écrire un prénom correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail Commercial" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurMailCom">
                    <b-form-input
                            id="addFournisseurMailCom"
                            data-vv-name="addFourMailCom"
                            v-model="fournisseur.commercial_mail"
                            v-validate="{required:false, email:true}"
                            :state="validateState('addFourMailCom')"
                            aria-describedby="invalidMailCom"
                            placeholder="adresse mail du Commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidMailCom">Veuillez écrire une adresse mail correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Telephone Commercial" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurTelCom">
                    <b-form-input
                            id="addFournisseurTelCom"
                            data-vv-name="addFourTelCom"
                            v-model="fournisseur.commercial_tel"
                            v-validate="{required:false}"
                            :state="validateState('addFourTelCom')"
                            aria-describedby="invalidTelCom"
                            placeholder="Numéro de téléphone du Commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidTelCom">Veuillez écrire un numéro de téléphone correct</b-form-invalid-feedback>
                </b-form-group>

            </form>

        </b-modal>

    </div>
</template>

<script>
    import { eventBus } from '../../main';

    export default {

        name: 'ModalAddFournisseur',
        data() {
            return {
                fournisseur: [],
            }
        },
        mounted() {
        },
        methods: {
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },
            handleOkAddFour(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addFournisseur();
            },
            addFournisseur() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.post('/fournisseurs', {

                        })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal9.hide();
                                });

                                this.$bvToast.toast(`Fournisseur ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                this.categorie = [];
                                eventBus.$emit('addedFournisseur');
                            })
                            .catch(error => {
                                console.log(error.response);
                            });
                    }
                });
            },

        }
    }
</script>