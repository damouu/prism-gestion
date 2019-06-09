<template>
    <div>

        <b-modal
                id="modal-EditExemplaire"
                title="Modification d'exemplaire"
                ref="modal3"
                scrollable
                size="lg"
                centered
                @ok="handleOkEditEx">
            <form ref="editExemplaire" @submit.stop.prevent="editExemplaire">
                <b-form-group label="Référence *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireRef">
                    <b-form-input
                            id="modExemplaireRef"
                            name="modExemplaire.reference"
                            v-model="modExemplaire.reference"
                            v-validate="{required:true}"
                            :state="validateState('modExemplaire.reference')"
                            aria-describedby="invalidModReference"
                            placeholder="Utilisez la douchette ou écrivez"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModReference">Veuillez écrire une référence ou scannez-la</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prix Achat HT *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireHt">
                    <b-form-input
                            id="modExemplaireHt"
                            data-vv-name="modExHt"
                            v-model="modExemplaire.prix_ht"
                            v-validate="{required:true, decimal:2}"
                            :state="validateState('modExHt')"
                            aria-describedby="invalidHt"
                            placeholder="Prix d'achat HT de l'exemplaire (en euros)"
                            type="number" step="any" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidHt">Vous devez entrer une valeur numérique avec 2 décimales</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prix Achat TTC *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireTtc">
                    <b-form-input
                            id="modExemplaireTtc"
                            data-vv-name="modExTtc"
                            v-model="modExemplaire.prix_ttc"
                            v-validate="{required:true, decimal:2}"
                            :state="validateState('modExTtc')"
                            aria-describedby="invalidTtc"
                            placeholder="Prix d'achat TTC de l'exemplaire (en euros)"
                            type="number" step="any" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidTtc">Vous devez entrer une valeur numérique avec 2 décimales</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Numéro de Série *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireNumSerie">
                    <b-form-input
                            id="modExemplaireNumSerie"
                            name="modExemplaire.num_serie"
                            v-model="modExemplaire.num_serie"
                            v-validate="{required:true}"
                            :state="validateState('modExemplaire.num_serie')"
                            aria-describedby="invalidModNumSerie"
                            placeholder="Numéro de série de l'exemplaire"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModNumSerie">Vous devez entrer un numéro de série</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Lieu de stockage *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireStockage">
                    <b-form-input
                            id="modExemplaireStockage"
                            name="modExemplaire.stockage"
                            v-model="modExemplaire.stockage"
                            v-validate="{required:true}"
                            :state="validateState('modExemplaire.stockage')"
                            aria-describedby="invalidModStockage"
                            placeholder="Lieu de stockage de l'exemplaire"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModStockage">Vous devez entrer un lieu de stockage</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="URL Source" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireUrl">
                    <b-form-input
                            id="modExemplaireUrl"
                            name="modExemplaire.url"
                            v-model="modExemplaire.url"
                            v-validate="{required:false, url:{require_protocol: true}}"
                            :state="validateState('modExemplaire.url')"
                            aria-describedby="invalidModUrl"
                            placeholder="url source"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModUrl">Vous devez entrer un URL valide</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Date d'achat *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireDateAchat">
                    <b-form-input
                            id="modExemplaireDateAchat"
                            name="modExemplaire.date_achat"
                            v-model="modExemplaire.date_achat"
                            v-validate="'required:true|date_format:yyyy-MM-dd'"
                            :state="validateState('modExemplaire.date_achat')"
                            aria-describedby="invalidModDateAchat"
                            type="date" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModDateAchat">Vous devez entrer une date au format DD/MM/AAAA valide.</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Etat *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireEtat">
                    <b-form-select
                            id="modExemplaireEtat"
                            name="modExemplaire.etat"
                            v-model="modExemplaire.etat"
                            v-validate="{required:true}"
                            :state="validateState('modExemplaire.etat')"
                            aria-describedby="invalidModEtat"
                            required>
                        <option>disponible</option>
                        <option>réparation</option>
                        <option>emprunté</option>
                        <option>non empruntable</option>
                    </b-form-select>
                    <b-form-invalid-feedback id="invalidModEtat">Vous devez choisir une option d'état.</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Fournisseur *" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireFournisseur">
                    <b-form-select
                            id="modExemplaireFournisseur"
                            name="modExemplaire.fournisseur"
                            v-model="modExemplaireFournisseur"
                            v-validate="{required:true}"
                            :state="validateState('modExemplaire.fournisseur')"
                            aria-describedby="invalidModFournisseur"
                            required>
                        <option v-for="option in fournisseurs" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </b-form-select>
                    <b-form-invalid-feedback id="invalidModFournisseur">Vous devez choisir un fournisseur.</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Financement" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireFinancement">
                    <b-form-input
                            id="modExemplaireFinancement"
                            data-vv-name="modExFinancement"
                            v-model="modExemplaire.financement"
                            v-validate="{required:false}"
                            :state="validateState('modExFinancement')"
                            aria-describedby="invalidFinancement"
                            placeholder="Nom du financeur"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidFinancement">Vous devez entrer un nom de financement</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Bon de commande" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireCommande">
                    <b-form-input
                            id="modExemplaireCommande"
                            data-vv-name="modExCommande"
                            v-model="modExemplaire.bon_commande"
                            v-validate="{required:false}"
                            :state="validateState('modExCommande')"
                            aria-describedby="invalidCommande"
                            placeholder="Numéro de commande"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCommande">Vous devez entrer un numéro de commande</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Immobilisation" label-cols-sm="4" label-align-sm="left" label-for="modExemplaireImmobilisation">
                    <b-form-input
                            id="modExemplaireImmobilisation"
                            data-vv-name="modExImmobilisation"
                            v-model="modExemplaire.immobilisation"
                            v-validate="{required:false}"
                            :state="validateState('modExImmobilisation')"
                            aria-describedby="invalidImmobilisation"
                            placeholder="Numéro d'immobilisation"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidImmobilisation">Vous devez entrer un numéro d'immobilisation</b-form-invalid-feedback>
                </b-form-group>


                <span class="text-danger">* champs obligatoires</span>

            </form>

        </b-modal>


    </div>
</template>



<script>

    import { eventBus } from '../../main';

    export default {
        name: 'ModalEditExemplaire',
        data() {
            return {

                materielId: false,

                modExemplaire: [],
                modExemplaireFournisseur: [],

                fournisseurs: [],
            }
        },
        mounted() {
            eventBus.$on('editExemplaire', data => {
                this.fournisseurs = JSON.parse(JSON.stringify(data.fournisseurs));
                this.modExemplaire = JSON.parse(JSON.stringify(data.exemplaire));
                this.modExemplaireFournisseur = JSON.parse(JSON.stringify(data.fournisseurExemplaire.value));
            });

        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            // Clic sur Valider Editer
            handleOkEditEx(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.editExemplaire();
            },

            // Modification exemplaire
            editExemplaire() {
                this.$validator.validateAll().then((result) => {
                    if (!result)
                        return;
                    else {
                        axios.put('/exemplaires/'+this.modExemplaire.id,{
                            materiel: this.modExemplaire.materiel,
                            reference: this.modExemplaire.reference,
                            prix_ht: this.modExemplaire.prix_ht,
                            prix_ttc: this.modExemplaire.prix_ttc,
                            num_serie: this.modExemplaire.num_serie,
                            stockage: this.modExemplaire.stockage,
                            url: this.modExemplaire.url,
                            financement: this.modExemplaire.financement,
                            bon_commande: this.modExemplaire.bon_commande,
                            immobilisation: this.modExemplaire.immobilisation,
                            date_achat: this.modExemplaire.date_achat,
                            etat: this.modExemplaire.etat,
                            fournisseur: this.modExemplaireFournisseur,
                        })
                        .then( response => {

                            this.$nextTick(() => {
                                this.$refs.modal3.hide();
                            });
                            this.$bvToast.toast(`Exemplaire modifié avec succès !`, {
                                title: `Modification réussie`,
                                toaster: 'b-toaster-bottom-right',
                                solid:true,
                                variant:'success',
                                appendToast: false
                            });

                            eventBus.$emit('editedExemplaire', {'exemplaire':this.modExemplaire,'fournisseurExemplaire':this.modExemplaireFournisseur});

                            this.fournisseurs = [];
                            this.modExemplaire = [];
                            this.modExemplaireFournisseur = [];
                        })
                        .catch( error => {

                            console.log(error);
                            eventBus.$emit('error', {
                                'error': error.response.statusText,
                                'status': error.response.status,
                                'message': error.response.data.message
                            });
                        });
                    }
                });
            },
        }
    }
</script>