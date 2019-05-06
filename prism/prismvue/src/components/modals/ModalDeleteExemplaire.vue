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
            <b-form ref="delExemplaire">
                <div>
                    <h4>Etes vous sûrs de vouloir supprimer l'exemplaire n° {{  }} ?</h4>
                    <p class="text-dark">Vous n'aurez plus aucun accès à cet exemplaire par la suite.</p>
                </div>
            </b-form>

        </b-modal>

    </div>
</template>

<script>

    import { eventBus } from '../../main';

    export default {
        name: 'ModalDeleteExemplaire',
        data() {
            return {
                exemplaire: [],
            }
        },
        mounted() {
            eventBus.$on('delExemplaire', data => {
                this.exemplaire = data.exemplaire;
            });
        },
        methods: {

            handleOkDel(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteMateriel();
            },

            deleteMateriel() {

                axios.delete('/exemplaires/'+this.exemplaire.id)
                    .then( response => {
                        this.$nextTick(() => {
                            this.$refs.modal4.hide();
                        });
                        eventBus.$emit('deleteSuccessExemplaire');
                    })
                    .catch( error => {
                        eventBus.$emit('deleteError', {'object':'exemplaire' , 'id':this.exemplaire.reference});
                    });
            }

        }
    }
</script>