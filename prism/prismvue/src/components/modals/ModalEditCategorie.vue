<template>
    <div id="modalEditCategorie">

        <b-modal
                id="modal-EditCategorie"
                title="Edition de catégorie"
                ref="modal14"
                scrollable
                size="lg"
                centered
                @ok="handleOkEditCat">
            <form ref="editCategorie" @submit.stop.prevent="editCategorie">
                <b-form-group label="Nom *" label-cols-sm="4" label-align-sm="left" label-for="editCategorieNom">
                    <b-form-input
                            id="editCategorieNom"
                            data-vv-name="editCatNom"
                            v-model="categorie.nom"
                            v-validate="{required:true}"
                            :state="validateState('editCatNom')"
                            aria-describedby="invalidNom"
                            placeholder="Entrez le nom de catégorie"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNom">Veuillez écrire un nom de catégorie.</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Couleur *" label-cols-sm="4" label-align-sm="left" label-for="editCategorieCouleur">
                    <colorPicker
                            id="addCategorieCouleur"
                            v-model="categorie.couleur"
                            :startColor="categorie.couleur"
                            :disabled="false">
                    </colorPicker>
                </b-form-group>

                <span class="text-danger">* champs obligatoires</span>
            </form>

        </b-modal>

    </div>
</template>

<script>
    import { eventBus } from '../../main';
    import ColorPicker from 'vue-color-picker-wheel';

    export default {

        name: 'ModalEditCategorie',
        components:{
            ColorPicker,
        },
        data() {
            return {
                categorie: [],
            }
        },
        mounted() {
            eventBus.$on('editCategorie', data => {
                this.categorie = data;
            });
        },
        methods: {
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },
            handleOkEditCat(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.editCategorie();
            },
            editCategorie() {
                this.$validator.validateAll().then( result => {
                    if (!result)
                    {
                        return;
                    }
                    else {
                        axios.put('/types/'+this.categorie.id, {
                            'nom':this.categorie.nom,
                            'couleur': this.categorie.couleur,
                        })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal14.hide();
                                });

                                this.$bvToast.toast(`Catégorie modifiée avec succès !`, {
                                    title: `Modification réussie`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                this.categorie = [];
                                eventBus.$emit('editedCategorie');
                            })
                            .catch(error => {
                                eventBus.$emit('error', {
                                    'error': error.response.statusText,
                                    'status': error.response.status,
                                    'message': error.response.data.message
                                });
                            });
                    }
                });
            },


        }
    }
</script>