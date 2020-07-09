<template>
    <div>
        <b-modal
                id="modal-EditReforme"
                title="Modification de la réforme"
                ref="modal11"
                scrollable
                centered
                size="lg"
                @ok="handleOkEditReforme">
            <form ref="editReforme" @submit.stop.prevent="editReforme">
                <h4>Voulez vous remettre en service cet exemplaire réformé ?</h4>
            </form>
        </b-modal>
    </div>
</template>


<script>

    import {eventBus} from '../../main';

    export default {
        name: 'ModalEditReforme',
        data() {
            return {
                reforme: [],
            }
        },
        mounted() {
            eventBus.$on('editReforme', data => {
                this.reforme = data.reforme;
            });

        },
        methods: {

            handleOkEditReforme(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.editReforme();
            },

            editReforme() {
                axios.patch('https://iutnc-resamat.univ-lorraine.fr/api/exemplaires/' + this.reforme.id + '?select=reforme')
                    .then(response => {

                        this.$nextTick(() => {
                            this.$refs.modal11.hide();
                        });

                        this.$bvToast.toast(`Exemplaire rétabli avec succès !`, {
                            title: `Retour en service réussi`,
                            toaster: 'b-toaster-bottom-right',
                            solid: true,
                            variant: 'success',
                            appendToast: false
                        });

                        eventBus.$emit('editedReforme');
                    })
                    .catch(error => {
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    })
            }


        }
    }
</script>