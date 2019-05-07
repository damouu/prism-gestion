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
                    <b-form-invalid-feedback id="invalidReference">Veuillez écrire une référence ou scannez-la</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prix Achat *" label-cols-sm="4" label-align-sm="left" label-for="addExemplairePrix">
                    <b-form-input
                            id="addExemplairePrix"
                            data-vv-name="addExPrixAchat"
                            v-model="postExemplaire.prix_achat"
                            v-validate="{required:true, decimal:2}"
                            :state="validateState('addExPrixAchat')"
                            aria-describedby="invalidPrixAchat"
                            placeholder="Prix d'achat de l'exemplaire (en euros)"
                            type="text" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidPrixAchat">Vous devez entrer une valeur numérique avec 2 décimales</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Numéro de Série *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireNumSerie">
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
                    <b-form-invalid-feedback id="invalidNumSerie">Vous devez entrer un numéro de série</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Lieu de stockage *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireStockage">
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
                    <b-form-invalid-feedback id="invalidStockage">Vous devez entrer un lieu de stockage</b-form-invalid-feedback>
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
                <b-form-group label="Date d'achat *" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireDateAchat">
                    <b-form-input
                            id="addExemplaireDateAchat"
                            data-vv-name="addExDateAchat"
                            v-model="postExemplaire.date_achat"
                            v-validate="'required:true|date_format:yyyy-MM-dd'"
                            :state="validateState('addExDateAchat')"
                            aria-describedby="invalidDateAchat"
                            type="date" required>
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidDateAchat">Vous devez entrer une date au format DD/MM/AAAA valide.</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Etat" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireEtat">
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
                    <b-form-invalid-feedback id="invalidEtat">Vous devez choisir une option d'état.</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Fournisseur" label-cols-sm="4" label-align-sm="left" label-for="addExemplaireFournisseur">
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
                    <b-form-invalid-feedback id="invalidFournisseur">Vous devez choisir un fournisseur.</b-form-invalid-feedback>
                </b-form-group>

            </form>

        </b-modal>


    </div>
</template>



<script>

    import { eventBus } from '../../main';

    export default {
        name: 'ModalAddExemplaire',
        data() {
            return {

                materielId: false,
                postExemplaire: [],
                fournisseurs: [],
            }
        },
        mounted() {

            eventBus.$on('addExemplaire', data => {
                this.materielId = data.materielId;
                this.fournisseurs = data.fournisseurs;
            });

        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            // Clic sur OK Ajout exemplaire
            handleOkAddEx(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addExemplaire();
            },

            // Ajout exemplaire
            addExemplaire() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.post('/exemplaires',
                            {
                                "materiel": this.materielId,
                                "reference": this.postExemplaire.reference,
                                "etat": this.postExemplaire.etat,
                                "fournisseur": this.postExemplaire.fournisseur,
                                "prix_achat": this.postExemplaire.prix_achat,
                                "num_serie": this.postExemplaire.num_serie,
                                "date_achat": this.postExemplaire.date_achat,
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
                                    variant:'success',
                                    solid:true,
                                    appendToast: false
                                });
                                this.postExemplaire = [];
                            })
                            .catch(error => {
                                console.log(this.materielId);
                                console.log(this.postExemplaire);
                                console.log(error.response);
                            })
                    }
                });
            },

        }
    }
</script>