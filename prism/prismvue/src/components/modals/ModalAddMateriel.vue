<template>
    <div>

        <b-modal
                id="modal-AddMateriel"
                title="Ajout de matériel"
                ref="modal5"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddMat">
            <form ref="addMateriel" @submit.stop.prevent="addMateriel">

            </form>

        </b-modal>


    </div>
</template>



<script>

    import { eventBus } from '../../main';

    export default {
        name: 'ModalAddMateriel',
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            // Clic sur OK Ajout Materiel
            handleOkAddMat(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addMateriel();
            },

            // Ajout materiel
            addMateriel() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.post('/materiels',
                            {

                            })
                            .then(response => {


                                this.$nextTick(() => {
                                    this.$refs.modal5.hide();
                                });
                            })
                            .catch(error => {

                            })
                    }
                });
            },

        }
    }
</script>