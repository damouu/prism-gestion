<template>
    <div>

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
                            data-vv-name="addExRef"
                            v-model="postExemplaire.reference"
                            v-validate="{required:true}"
                            :state="validateState('addExRef')"
                            aria-describedby="invalidReference"
                            placeholder="Utilisez la douchette ou écrivez"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidReference">Veuillez écrire une référence ou scannez-la
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prix Achat HT *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireHt">
                    <b-form-input
                            id="addExemplaireHt"
                            data-vv-name="addExHt"
                            v-model="postExemplaire.prix_ht"
                            v-validate="{required:true, decimal:2}"
                            :state="validateState('addExHt')"
                            aria-describedby="invalidHt"
                            placeholder="Prix d'achat HT de l'exemplaire (en euros)"
                            type="number" step="any" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidHt">Vous devez entrer une valeur numérique avec 2 décimales
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prix Achat TTC *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireTtc">
                    <b-form-input
                            id="addExemplaireTtc"
                            data-vv-name="addExTtc"
                            v-model="postExemplaire.prix_ttc"
                            v-validate="{required:true, decimal:2}"
                            :state="validateState('addExTtc')"
                            aria-describedby="invalidTtc"
                            placeholder="Prix d'achat TTC de l'exemplaire (en euros)"
                            type="number" step="any" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidTtc">Vous devez entrer une valeur numérique avec 2 décimales
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Numéro de Série *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireNumSerie">
                    <b-form-input
                            id="addExemplaireNumSerie"
                            data-vv-name="addExNumSerie"
                            v-model="postExemplaire.num_serie"
                            v-validate="{required:true}"
                            :state="validateState('addExNumSerie')"
                            aria-describedby="invalidNumSerie"
                            placeholder="Numéro de série de l'exemplaire"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNumSerie">Vous devez entrer un numéro de série
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Lieu de stockage *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireStockage">
                    <b-form-input
                            id="addExemplaireStockage"
                            data-vv-name="addExStockage"
                            v-model="postExemplaire.stockage"
                            v-validate="{required:true}"
                            :state="validateState('addExStockage')"
                            aria-describedby="invalidStockage"
                            placeholder="Lieu de stockage de l'exemplaire"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidStockage">Vous devez entrer un lieu de stockage
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="URL Source" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireUrl">
                    <b-form-input
                            id="addExemplaireUrl"
                            data-vv-name="addExUrl"
                            v-model="postExemplaire.url"
                            v-validate="{required:false, url:{require_protocol: true}}"
                            :state="validateState('addExUrl')"
                            aria-describedby="invalidUrl"
                            placeholder="url source"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidUrl">Vous devez entrer un URL valide</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Date d'achat *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireDateAchat">
                    <b-row v-show="date_achat!=='9999-12-31 11:59 pm'">
                        <b-col cols="6">
                            <VueCtkDateTimePicker
                                    id="addExemplaireDateAchat"
                                    :dateFormat="'YYYY-MM-DD'"
                                    onlyDate
                                    formatted="ll"
                                    noLabel
                                    v-model="date_achat"
                                    aria-describedby="invalidDateAchat">
                            </VueCtkDateTimePicker>
                        </b-col>
                        <b-col cols="6">
                            <b-button @click="inconnue()">Date inconnue</b-button>
                        </b-col>
                    </b-row>
                    <b-row v-show="date_achat==='9999-12-31 11:59 pm'">
                        <b-col cols="6">
                            <p>{{date_achat}}</p>
                        </b-col>
                        <b-col cols="6">
                            <b-button @click="connue()">Date connue</b-button>
                        </b-col>
                    </b-row>

                    <b-form-invalid-feedback id="invalidDateAchat">Vous devez entrer une date au format DD/MM/AAAA
                        valide.
                    </b-form-invalid-feedback>
                </b-form-group>


                <b-form-group label="Etat *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireEtat">
                    <b-form-select
                            id="addExemplaireEtat"
                            data-vv-name="addExEtat"
                            v-model="postExemplaire.etat"
                            v-validate="{required:true}"
                            :state="validateState('addExEtat')"
                            aria-describedby="invalidEtat"
                            required>
                        <option>disponible</option>
                        <option>réparation</option>
                        <option>emprunté</option>
                        <option>non empruntable</option>
                    </b-form-select>
                    <b-form-invalid-feedback id="invalidEtat">Vous devez choisir une option d'état.
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Fournisseur *" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireFournisseur">
                    <b-form-select
                            id="addExemplaireFournisseur"
                            data-vv-name="addExFournisseur"
                            v-model="postExemplaire.fournisseur"
                            v-validate="{required:true}"
                            :state="validateState('addExFournisseur')"
                            aria-describedby="invalidFournisseur"
                            required>
                        <option v-for="option in fournisseurs" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </b-form-select>
                    <b-form-invalid-feedback id="invalidFournisseur">Vous devez choisir un fournisseur.
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Financement" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireFinancement">
                    <b-form-input
                            id="addExemplaireFinancement"
                            data-vv-name="addExFinancement"
                            v-model="postExemplaire.financement"
                            v-validate="{required:false}"
                            :state="validateState('addExFinancement')"
                            aria-describedby="invalidFinancement"
                            placeholder="Nom du financeur"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidFinancement">Vous devez entrer un nom de financement
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Bon de commande" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireCommande">
                    <b-form-input
                            id="addExemplaireCommande"
                            data-vv-name="addExCommande"
                            v-model="postExemplaire.bon_commande"
                            v-validate="{required:false}"
                            :state="validateState('addExCommande')"
                            aria-describedby="invalidCommande"
                            placeholder="Numéro de commande"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCommande">Vous devez entrer un numéro de commande
                    </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Immobilisation" label-cols-sm="4" label-align-sm="left"
                              label-for="addExemplaireImmobilisation">
                    <b-form-input
                            id="addExemplaireImmobilisation"
                            data-vv-name="addExImmobilisation"
                            v-model="postExemplaire.immobilisation"
                            v-validate="{required:false}"
                            :state="validateState('addExImmobilisation')"
                            aria-describedby="invalidImmobilisation"
                            placeholder="Numéro d'immobilisation"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidImmobilisation">Vous devez entrer un numéro d'immobilisation
                    </b-form-invalid-feedback>
                </b-form-group>

                <span class="text-danger">* champs obligatoires</span>
            </form>

        </b-modal>


    </div>
</template>


<script>

    import {eventBus} from '../../main';

    export default {
        name: 'ModalAddExemplaire',
        data() {
            return {

                materielId: false,
                postExemplaire: [],
                fournisseurs: [],
                date_achat: null,
            }
        },
        mounted() {

            // evenement
            eventBus.$on('addExemplaire', data => {
                this.materielId = data.materielId;
                this.fournisseurs = data.fournisseurs;
            });

        },
        methods: {

            //validation client
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            // Clic sur OK Ajout exemplaire
            handleOkAddEx(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addExemplaire();
            },
            inconnue() {
                this.date_achat = "9999-12-31 11:59 pm";
                this.$nextTick(() => {
                    this.date_achat = "9999-12-31 11:59 pm";
                });
            },
            connue() {
                this.date_achat = null;
                this.$nextTick(() => {
                    this.date_achat = null;
                });
            },

            // Ajout exemplaire
            addExemplaire() {
                this.$validator.validateAll().then(result => {
                    if (!result && (this.date_achat === null || this.date_achat === undefined)) {
                        return;
                    } else {
                        let date = this.date_achat.split(' ');
                        console.log(date['0']);
                        axios.post('https://iutnc-resamat.univ-lorraine.fr/api/exemplaires/',
                            {
                                "materiel": this.materielId,
                                "reference": this.postExemplaire.reference,
                                "etat": this.postExemplaire.etat,
                                "fournisseur": this.postExemplaire.fournisseur,
                                "prix_ht": this.postExemplaire.prix_ht,
                                "prix_ttc": this.postExemplaire.prix_ttc,
                                "num_serie": this.postExemplaire.num_serie,
                                "financement": this.postExemplaire.financement,
                                "bon_commande": this.postExemplaire.bon_commande,
                                "immobilisation": this.postExemplaire.immobilisation,
                                "date_achat": date['0'],
                                "stockage": this.postExemplaire.stockage,
                                "url": this.postExemplaire.url
                            })
                            .then(response => {
                                this.$nextTick(() => {
                                    this.$refs.modal2.hide();
                                });

                                eventBus.$emit('addedExemplaire', {'exemplaire': this.postExemplaire});

                                this.$bvToast.toast(`Exemplaire ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                this.postExemplaire = [];
                            })
                            .catch(error => {
                                eventBus.$emit('error', {
                                    'error': error.response.statusText,
                                    'status': error.response.status,
                                    'message': error.response.data.message
                                });
                            })
                    }
                });
            },

        }
    }
</script>