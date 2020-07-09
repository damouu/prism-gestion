<template>
    <div id="ModalAddReservationProfesseur">

        <b-modal
                id="modal-AddResaProf"
                title="Ajout d'un professeur"
                ref="modal12"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddResaProf">

            <form ref="addProfesseur" @submit.stop.prevent="addProfesseur">

                <b-form-group label="Nom *" label-for="addProfNom" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addProfNom"
                            v-model="professeur.nom"
                            v-validate="{required:true}"
                            data-vv-name="addProfNom"
                            :state="validateState('addProfNom')"
                            aria-describedby="invalidProfNom"
                            placeholder="Entrez le nom du professeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidProfNom">Veuillez écrire un nom de Professeur
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Prenom *" label-for="addProfPrenom" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addProfPrenom"
                            v-model="professeur.prenom"
                            v-validate="{required:true}"
                            data-vv-name="addProfPrenom"
                            :state="validateState('addProfPrenom')"
                            aria-describedby="invalidProfPrenom"
                            placeholder="Entrez le prénom du professeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidProfPrenom">Veuillez écrire un prénom de Professeur
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Téléphone *" label-for="addProfTel" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addProfTel"
                            v-model="professeur.telephone"
                            v-validate="{required:true}"
                            data-vv-name="addProfTel"
                            :state="validateState('addProfTel')"
                            aria-describedby="invalidProfTel"
                            placeholder="Entrez le numéro de téléphone du professeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidProfTel">Veuillez écrire le numéro de téléphone du Professeur
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Mail *" label-for="addProfMail" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addProfMail"
                            v-model="professeur.mail"
                            v-validate="{required:true}"
                            data-vv-name="addProfMail"
                            :state="validateState('addProfMail')"
                            aria-describedby="invalidProfMail"
                            placeholder="Entrez le mail du professeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidProfMail">Veuillez écrire le mail du Professeur
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
        name: 'ModalAddReservationProfesseur',
        data() {
            return {
                professeur: [],
            }
        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },


            handleOkAddResaProf(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addProfesseur();
            },

            addProfesseur() {
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        return;
                    } else {
                        axios.post('https://iutnc-resamat.univ-lorraine.fr/api/professeurs/',
                            {
                                'nom': this.professeur.nom,
                                'prenom': this.professeur.prenom,
                                'telephone': this.professeur.telephone,
                                'mail': this.professeur.mail
                            })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal12.hide();
                                });

                                this.$bvToast.toast(`Professeur ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                eventBus.$emit('addedProf', this.professeur);

                            })
                            .catch(error => {
                                this.$nextTick(() => {
                                    this.$refs.modal12.hide();
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