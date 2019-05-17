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

                <b-form-group label="Constructeur *" label-for="addMatCons" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addMatCons"
                            v-model="materiel.constructeur"
                            v-validate="{required:true}"
                            data-vv-name="addCons"
                            :state="validateState('addCons')"
                            aria-describedby="invalidCons"
                            placeholder="Entrez le nom du constructeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCons">Veuillez écrire un Cosntructeur</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Modele *" label-for="addMatMod" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="addMatMod"
                            v-model="materiel.modele"
                            v-validate="{required:true}"
                            data-vv-name="addMod"
                            :state="validateState('addMod')"
                            aria-describedby="invalidModele"
                            placeholder="Entrez le nom du modèle"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModele">Veuillez écrire un modèle</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Type *" label-for="addMatType" label-cols-sm="4" label-align-sm="left">
                    <b-form-select
                            id="addMatType"
                            v-model="materiel.type"
                            v-validate="{required:true}"
                            data-vv-name="addType"
                            :state="validateState('addType')"
                            aria-describedby="invalidType">
                        <option v-for="option in types" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                        <b-form-invalid-feedback id="invalidType">Veuillez choisir un type</b-form-invalid-feedback>
                    </b-form-select>
                </b-form-group>

                <b-form-group label="Description" label-for="addMatDes" label-cols-sm="4" label-align-sm="left">
                    <b-form-textarea
                            id="addMatDesc"
                            data-vv-name="addDes"
                            v-validate="{required:false}"
                            v-model="materiel.description"
                            placeholder="Ecrivez une description du matériel"
                            rows="2">
                    </b-form-textarea>
                </b-form-group>

                <span class="text-danger">* champs obligatoires</span>
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
                materiel: [],
                types: [],
            }
        },
        mounted() {

            eventBus.$on('addMateriel', data => {
                this.types = [];
                this.getTypes();
            });

        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            getTypes() {
                axios.get('/types/' )
                    .then( response => {
                        for(let data of response.data.types) {
                            this.types.push({'value':data.id, 'text':data.nom});
                        }
                    })
                    .catch( error => {
                        eventBus.$emit('error', {
                            'error': error.response.statusText,
                            'status': error.response.status,
                            'message': error.response.data.message
                        });
                    });
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
                                'constructeur': this.materiel.constructeur,
                                'modele': this.materiel.modele,
                                'description': this.materiel.description,
                                'type': this.materiel.type,
                            })
                            .then(response => {

                                this.$nextTick(() => {
                                    this.$refs.modal5.hide();
                                });

                                this.$bvToast.toast(`Matériel ajouté avec succès !`, {
                                    title: `Ajout réussi`,
                                    toaster: 'b-toaster-bottom-right',
                                    variant:'success',
                                    solid:true,
                                    appendToast: false
                                });
                                this.materiel = [];
                                eventBus.$emit('addedMateriel');

                            })
                            .catch(error => {
                                eventBus.$emit('error', {
                                    'error': error.response.statusText,
                                    'status': error.response.status,
                                    'message': error.response.data.message
                                });
                            })
                    }
                });
            },

        }
    }
</script>