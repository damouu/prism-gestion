<template>
    <div id="modalEditFournisseur">

        <b-modal
                id="modal-EditFournisseur"
                title="Modification de Fournisseur"
                ref="modal10"
                scrollable
                size="lg"
                centered
                @ok="handleOkEditFour">
            <form ref="EditFournisseur" @submit.stop.prevent="editFournisseur">

                <b-form-group label="Nom *" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurNom">
                    <b-form-input
                            id="editFournisseurNom"
                            data-vv-name="editFourNom"
                            v-model="fournisseur.nom"
                            v-validate="{required:true}"
                            :state="validateState('editFourNom')"
                            aria-describedby="invalidNom"
                            placeholder="Nom du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNom">Veuillez écrire un nom de fournisseur</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Adresse *" label-cols-sm="4" label-align-sm="left" label-for="addFournisseurAdresse">
                    <b-form-input
                            id="addFournisseurAdresse"
                            data-vv-name="editFourAdresse"
                            v-model="fournisseur.adresse"
                            v-validate="{required:true}"
                            :state="validateState('editFourAdresse')"
                            aria-describedby="invalidAdresse"
                            placeholder="Adresse Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidAdresse">Veuillez écrire une adresse</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Ville *" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurVille">
                    <b-form-input
                            id="editFournisseurVille"
                            data-vv-name="editFourVille"
                            v-model="fournisseur.ville"
                            v-validate="{required:true}"
                            :state="validateState('editFourVille')"
                            aria-describedby="invalidVille"
                            placeholder="Ville Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidVille">Veuillez écrire une ville</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Code postal *" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurCodePostal">
                    <b-form-input
                            id="editFournisseurCodePostal"
                            data-vv-name="editFourCodePostal"
                            v-model="fournisseur.code_postal"
                            v-validate="{required:true}"
                            :state="validateState('editFourCodePostal')"
                            aria-describedby="invalidCodePostal"
                            placeholder="Code postal Fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCodePostal">Veuillez écrire un code postal</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail *" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurMail">
                    <b-form-input
                            id="editFournisseurMail"
                            data-vv-name="editFourMail"
                            v-model="fournisseur.mail"
                            v-validate="{required:true, email:true}"
                            :state="validateState('editFourMail')"
                            aria-describedby="invalidMail"
                            placeholder="adresse mail du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidMail">Veuillez écrire une adresse mail correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Telephone *" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurTel">
                    <b-form-input
                            id="editFournisseurTel"
                            data-vv-name="editFourTel"
                            v-model="fournisseur.tel"
                            v-validate="{required:true}"
                            :state="validateState('editFourTel')"
                            aria-describedby="invalidTel"
                            placeholder="Numéro de téléphone du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidTel">Veuillez écrire un numéro de téléphone correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Site Web" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurSiteWeb">
                    <b-form-input
                            id="editFournisseurSiteWeb"
                            data-vv-name="editFourSiteWeb"
                            v-model="fournisseur.site_web"
                            v-validate="{required:false, url:{require_protocol: true}}"
                            :state="validateState('editFourSiteWeb')"
                            aria-describedby="invalidSiteWeb"
                            placeholder="site web du fournisseur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidSiteWeb">Veuillez écrire un site web correct</b-form-invalid-feedback>
                </b-form-group>


                <b-form-group label="Nom Commercial" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurNomCom">
                    <b-form-input
                            id="editFournisseurNomCom"
                            data-vv-name="editFourNomCom"
                            v-model="fournisseur.commercial_nom"
                            v-validate="{required:false}"
                            :state="validateState('editFourNomCom')"
                            aria-describedby="invalidNomCom"
                            placeholder="Nom du commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNomCom">Veuillez écrire un nom correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prénom Commercial" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurPrenomCom">
                    <b-form-input
                            id="editFournisseurPrenomCom"
                            data-vv-name="editFourPrenomCom"
                            v-model="fournisseur.commercial_prenom"
                            v-validate="{required:false}"
                            :state="validateState('editFourPrenomCom')"
                            aria-describedby="invalidPrenomCom"
                            placeholder="Prénom du commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidPrenomCom">Veuillez écrire un prénom correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail Commercial" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurMailCom">
                    <b-form-input
                            id="editFournisseurMailCom"
                            data-vv-name="editFourMailCom"
                            v-model="fournisseur.commercial_mail"
                            v-validate="{required:false, email:true}"
                            :state="validateState('editFourMailCom')"
                            aria-describedby="invalidMailCom"
                            placeholder="adresse mail du Commercial"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidMailCom">Veuillez écrire une adresse mail correct</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Telephone Commercial" label-cols-sm="4" label-align-sm="left" label-for="editFournisseurTelCom">
                    <b-form-input
                            id="editFournisseurTelCom"
                            data-vv-name="editFourTelCom"
                            v-model="fournisseur.commercial_tel"
                            v-validate="{required:false}"
                            :state="validateState('editFourTelCom')"
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

        name: 'ModalEditFournisseur',
        data() {
            return {
                fournisseur: [],
            }
        },
        mounted() {

            eventBus.$on('editFournisseur', data => {
               this.fournisseur = JSON.parse(JSON.stringify(data.fournisseur));
            });

        },
        methods: {
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },
            handleOkEditFour(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.editFournisseur();
            },
            editFournisseur() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.put('/fournisseurs/'+this.fournisseur.id, {
                            'nom': this.fournisseur.nom,
                            'adresse': this.fournisseur.adresse,
                            'ville': this.fournisseur.ville,
                            'code_postal': this.fournisseur.code_postal,
                            'mail': this.fournisseur.mail,
                            'tel': this.fournisseur.tel,
                            'site_web': this.fournisseur.site_web,
                            'commercial_nom': this.fournisseur.commercial_nom,
                            'commercial_prenom': this.fournisseur.commercial_prenom,
                            'commercial_tel': this.fournisseur.commercial_tel,
                            'commercial_mail': this.fournisseur.commercial_mail,
                        })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal10.hide();
                                });

                                this.$bvToast.toast(`Fournisseur ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                eventBus.$emit('editedFournisseur');
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