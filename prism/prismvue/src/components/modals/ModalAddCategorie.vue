<template>
    <div id="modalAddCategorie">

        <b-modal
                id="modal-AddCategorie"
                title="Ajout de catégorie"
                ref="modal6"
                scrollable
                size="lg"
                centered
                @ok="handleOkAddCat">
            <form ref="addCategorie" @submit.stop.prevent="addCategorie">
                <b-form-group label="Nom *" label-cols-sm="4" label-align-sm="left" label-for="addCategorieNom">
                    <b-form-input
                            id="addCategorieNom"
                            data-vv-name="addCatNom"
                            v-model="categorie.nom"
                            v-validate="{required:true}"
                            :state="validateState('addCatNom')"
                            aria-describedby="invalidNom"
                            placeholder="Entrez le nom de catégorie"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidNom">Veuillez écrire un nom de catégorie.
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Couleur *" label-cols-sm="4" label-align-sm="left" label-for="addCategorieCouleur">
                    <colorPicker
                            id="addCategorieCouleur"
                            v-model="color"
                            startColor="#ff"
                            :disabled="false"
                    >
                    </colorPicker>
                </b-form-group>

                <span class="text-danger">* champs obligatoires</span>
            </form>

        </b-modal>

    </div>
</template>

<script>
    import {eventBus} from '../../main';
    import ColorPicker from 'vue-color-picker-wheel';

    export default {

        name: 'ModalAddCategorie',
        components: {
            ColorPicker,
        },
        data() {
            return {
                categorie: [],
                color: '',
            }
        },
        mounted() {
        },
        methods: {
            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },
            handleOkAddCat(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.addCategorie();
            },
            addCategorie() {
                this.$validator.validateAll().then(result => {
                    if (!result || !this.color) {
                        return;
                    } else {
                        axios.post('https://iutnc-resamat.univ-lorraine.fr/api/types/', {
                            'nom': this.categorie.nom,
                            'couleur': this.color
                        })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal6.hide();
                                });

                                this.$bvToast.toast(`Catégorie ajoutée avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant: 'success',
                                    solid: true,
                                    appendToast: false
                                });
                                this.categorie = [];
                                eventBus.$emit('addedCategorie');
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