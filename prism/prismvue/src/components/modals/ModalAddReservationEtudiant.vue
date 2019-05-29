<template>
    <div id="ModalAddReservationEtudiant">

        <b-modal
                id="modal-AddResaEtu"
                title="Ajout d'un étudiant"
                ref="modal13"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddResaEtu">

            <form ref="addEtudiant" @submit.stop.prevent="addEtudiant">

                <b-form-group label="Nom *" label-for="addEtuNom" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addEtuNom"
                            v-model="etudiant.nom"
                            v-validate="{required:true, regex:/^[a-zé7àùûêâôëîï\s-]{1}[a-zé7àùûêâôëîï\'-]*[a-zé7àùûêâôëîï]$/i}"
                            data-vv-name="addEtuNom"
                            :state="validateState('addEtuNom')"
                            aria-describedby="invalidEtuNom"
                            placeholder="Entrez le nom du etudiant"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidEtuNom">Veuillez écrire un nom de Etudiant</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prenom *" label-for="addEtuPrenom" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addEtuPrenom"
                            v-model="etudiant.prenom"
                            v-validate="{required:true, regex:/^[a-zé7àùûêâôëîï\s-]{1}[a-zé7àùûêâôëîï\'-]*[a-zé7àùûêâôëîï]$/i}"
                            data-vv-name="addEtuPrenom"
                            :state="validateState('addEtuPrenom')"
                            aria-describedby="invalidEtuPrenom"
                            placeholder="Entrez le prénom du etudiant"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidEtuPrenom">Veuillez écrire un prénom de Etudiant</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Téléphone *" label-for="addEtuTel" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addEtuTel"
                            v-model="etudiant.telephone"
                            v-validate="{required:true,max:20, numeric:true}"
                            data-vv-name="addEtuTel"
                            :state="validateState('addEtuTel')"
                            aria-describedby="invalidEtuTel"
                            placeholder="Entrez le numéro de téléphone du etudiant"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidEtuTel">Veuillez écrire le numéro de téléphone du Etudiant</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail *" label-for="addEtuMail" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addEtuMail"
                            v-model="etudiant.mail"
                            v-validate="{required:false, email:true}"
                            data-vv-name="addEtuMail"
                            :state="validateState('addEtuMail')"
                            aria-describedby="invalidEtuMail"
                            placeholder="Entrez le mail du etudiant"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidEtuMail">Veuillez écrire le mail du Etudiant</b-form-invalid-feedback>
                </b-form-group>

                <span class="text-danger">* champs obligatoires</span>


            </form>

        </b-modal>

    </div>
</template>

<script>

    import { eventBus } from '../../main';

    export default {
        name:'ModalAddReservationEtudiant',
        data() {
            return {
                etudiant: [],
            }
        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },


            handleOkAddResaEtu(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addEtudiant();
            },

            addEtudiant() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.post('/etudiants',
                            {
                                'nom': this.etudiant.nom,
                                'prenom': this.etudiant.prenom,
                                'telephone': this.etudiant.telephone,
                                'mail': this.etudiant.mail
                            })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal13.hide();
                                });

                                this.$bvToast.toast(`Etudiant ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });

                                eventBus.$emit('addedEtu',response.data.etudiant);
                            })
                            .catch(error => {
                                this.$nextTick(() => {
                                    this.$refs.modal13.hide();
                                });
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