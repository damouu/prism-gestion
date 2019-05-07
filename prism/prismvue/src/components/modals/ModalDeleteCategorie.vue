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
                <b-form-group label="Selectionnez la catégorie à supprimer" label-for="delType" label-cols-sm="4" label-align-sm="left">
                    <b-form-select
                            id="delType"
                            v-model="typeSelected.id"
                            v-validate="{required:true}"
                            data-vv-name="deleteType"
                            :state="validateState('deleteType')"
                            aria-describedby="invalidType">
                        <option v-for="option in types" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                        <b-form-invalid-feedback id="invalidType">Veuillez choisir une catégorie</b-form-invalid-feedback>
                    </b-form-select>
                </b-form-group>
                <p class="text-dark">Vous n'aurez plus aucun accès à cette catégorie par la suite.</p>
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
                typeSelected: [],
                types: [],
            }
        },
        mounted() {

            eventBus.$on('deleteCategorie', data => {
                this.types = [];
                this.getTypes();
            });

        },
        methods: {

            handleOkDel(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteCategorie();
            },
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            deleteCategorie() {
                this.$validator.validateAll().then( result => {
                    if (!result) {
                        return;
                    } else {
                        axios.delete('/types/' + this.typeSelected.id)
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
                                eventBus.$emit('deleteError', {
                                    'error': error.response.statusText,
                                    'status': error.response.status,
                                    'message': error.response.data.message
                                });
                            });
                    }
                });

            },

            getTypes() {
                axios.get('/types/' )
                    .then( response => {
                        for(let data of response.data.types) {
                            this.types.push({'value':data.id, 'text':data.nom});
                        }
                    })
                    .catch( error => {
                        console.log(error)
                    });
            },

        }
    }
</script>