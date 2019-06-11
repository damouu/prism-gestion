<template>
    <div id="modalDeleteCategorie">

        <b-modal
                id="modal-DelCategorie"
                title="Suppression de catégorie"
                class="text-danger"
                ref="modal8"
                scrollable
                centered
                size="lg"
                @ok="handleOkDel">
            <b-form ref="delCategorie" @submit.stop.prevent="deleteCategorie">
                <h3>Vous êtes sur le point de supprimer la catégorie: <br> {{type.nom}} <b-badge v-bind:style="{ 'background-color':type.couleur}">{{type.couleur}}</b-badge></h3>
                <p class="text-danger">Vous n'aurez plus aucun accès à cette catégorie par la suite.</p>
            </b-form>

        </b-modal>

    </div>
</template>

<script>

    import { eventBus } from '../../main';

    export default {
        name: 'ModalDeleteCategorie',
        data() {
            return {
                type:[],
            }
        },
        mounted(){

            eventBus.$on('deleteCategorie', data => {
                this.type = data;
            });

        },
        methods: {

            handleOkDel(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteCategorie();
            },

            deleteCategorie() {
                axios.delete('/types/' + this.type.id)
                    .then(response => {
                        this.$nextTick(() => {
                            this.$refs.modal8.hide();
                        });
                        this.$bvToast.toast(`Catégorie supprimée avec succès !`, {
                            title: `Suppression réussie`,
                            toaster: 'b-toaster-bottom-right',
                            variant: 'success',
                            solid: true,
                            appendToast: false
                        });
                        eventBus.$emit('deleteSuccessCategorie');
                    })
                    .catch(error => {
                        this.$nextTick(() => {
                            this.$refs.modal8.hide();
                        });
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    });
            },

        }
    }
</script>