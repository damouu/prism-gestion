<template>
    <div id="modalDeleteExemplaire">

        <b-modal
                id="modal-DelExemplaire"
                title="Suppression d'exemplaire"
                class="text-danger"
                ref="modal4"
                scrollable
                centered
                size="lg"
                @ok="handleOkDel">
            <b-form ref="delExemplaire" @submit.stop.prevent="deleteExemplaire">
                <div>
                    <h3>Etes vous sûrs de vouloir supprimer l'exemplaire avec la référence " {{ exemplaire.reference }}
                        " ?</h3>
                    <p class="text-dark">Vous n'aurez plus aucun accès à cet exemplaire par la suite.</p>
                </div>
            </b-form>

        </b-modal>

    </div>
</template>

<script>

    import {eventBus} from '../../main';

    export default {
        name: 'ModalDeleteExemplaire',
        data() {
            return {
                exemplaire: [],
            }
        },
        mounted() {
            eventBus.$on('delExemplaire', data => {
                this.exemplaire = data.exemplaire;
            });
        },
        methods: {

            handleOkDel(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteExemplaire();
            },

            deleteExemplaire() {

                axios.delete('https://iutnc-resamat.univ-lorraine.fr/api/exemplaires/' + this.exemplaire.id)
                    .then(response => {
                        this.$nextTick(() => {
                            this.$refs.modal4.hide();
                        });
                        this.$bvToast.toast(`Exemplaire supprimé avec succès !`, {
                            title: `Suppression réussie`,
                            toaster: 'b-toaster-bottom-right',
                            variant: 'success',
                            solid: true,
                            appendToast: false
                        });
                        eventBus.$emit('deleteSuccessExemplaire');
                    })
                    .catch(error => {
                        this.$nextTick(() => {
                            this.$refs.modal4.hide();
                        });
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    });
            }

        }
    }
</script>