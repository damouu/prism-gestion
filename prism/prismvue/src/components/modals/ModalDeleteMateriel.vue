<template>
    <div>

        <b-modal
                id="modal-DeleteMateriel"
                title="Suppression de matériel"
                ref="modal7"
                scrollable
                class="text-danger"
                size="lg"
                centered
                @ok="handleOkDeleteMat">
            <form ref="deleteMateriel" @submit.stop.prevent="deleteMateriel">
                <div>
                    <h3>Etes vous sûrs de vouloir supprimer le matériel ?</h3>
                    <p class="text-dark">Vous n'aurez plus aucun accès à ce Materiel par la suite.</p>
                </div>
            </form>
        </b-modal>
    </div>
</template>


<script>
    import { eventBus } from '../../main';

    export default {
        name: "ModalDeleteMateriel",
        data() {
            return {
                materiel: [],
            }
        },
        mounted(){
            eventBus.$on('deleteMateriel', data => {
                this.materiel.id = data.id;
            })
        },
        methods: {
            handleOkDeleteMat(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteMateriel();
            },

            deleteMateriel() {
                axios.delete('/materiels/'+this.materiel.id)
                    .then(response => {

                        this.$nextTick(() => {
                            this.$refs.modal7.hide();
                        });
                        this.$bvToast.toast(`Materiel supprimé avec succès !`, {
                            title: `Suppression réussie`,
                            toaster: 'b-toaster-bottom-right',
                            variant:'success',
                            solid:true,
                            appendToast: false
                        });
                        eventBus.$emit('deletedMateriel');
                    })
                    .catch(error => {
                            this.$nextTick(() => {
                                this.$refs.modal7.hide();
                            });
                            eventBus.$emit('deleteError', {'error':error.response.statusText, 'status': error.response.status, 'message': error.response.data.message});
                    })
            }
        }
    }
</script>