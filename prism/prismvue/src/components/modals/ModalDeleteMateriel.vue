<template>
    <div>

        <b-modal
                id="modal-DeleteMateriel"
                title="Ajout de matériel"
                ref="modal7"
                scrollable
                size="lg"
                centered
                @ok="handleOkDeleteMat">
            <form ref="deleteMateriel" @submit.stop.prevent="deleteMateriel">


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
                            eventBus.$emit('deleteErrorMateriel', {'error':error.response.statusText, 'status': error.response.status, 'message': error.response.data.message});
                    })
            }
        }
    }
</script>