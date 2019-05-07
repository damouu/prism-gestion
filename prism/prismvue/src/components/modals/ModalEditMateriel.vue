<template>
    <div>

        <b-modal
                id="modal-Materiel"
                title="Modification de matériel"
                ref="modal1"
                scrollable
                centered
                size="lg"
                @ok="handleOkModif">
            <form ref="modifMateriel"@submit.stop.prevent="modifMateriel" >
                <b-form-group label="Constructeur *" label-for="consMatMod" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="consMatMod"
                            v-model="materielModif.constructeur"
                            v-validate="{required:true}"
                            data-vv-name="editMatCons"
                            :state="validateState('editMatCons')"
                            aria-describedby="invalidCons"
                            placeholder="Entrez le nom du constructeur"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidCons">Veuillez écrire un Cosntructeur</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Modele *" label-for="modMatMod" label-cols-sm="4" label-align-sm="left">
                    <b-form-input
                            id="modMatMod"
                            v-model="materielModif.modele"
                            v-validate="{required:true}"
                            name="test.editMatMod"
                            :state="validateState('test.editMatMod')"
                            aria-describedby="invalidModele"
                            placeholder="Entrez le nom du modèle"
                            type="text">
                    </b-form-input>
                    <b-form-invalid-feedback id="invalidModele">Veuillez écrire un modèle</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Type *" label-for="typeMatMod" label-cols-sm="4" label-align-sm="left">
                    <b-form-select
                            id="typeMatMod"
                            v-model="materielTypeModif"
                            v-validate="'required'"
                            name="test.editMatType"
                            :state="validateState('test.editMatType')"
                            aria-describedby="invalidType">
                        <option v-for="option in types" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                        <b-form-invalid-feedback id="invalidType">Veuillez choisir un type</b-form-invalid-feedback>
                    </b-form-select>
                </b-form-group>

                <b-form-group label="Description" label-for="modMatMod" label-cols-sm="4" label-align-sm="left">
                    <b-form-textarea
                            id="descriptionMatMod"
                            name="materielModif.description"
                            v-model="materielModif.description"
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
        name: 'ModalEditMateriel',
        data() {
            return {

                materielId: false,
                types: [],

                materiel: [],
                materielModif: [],
                materielTypeModif: [],
            }
        },
        mounted() {
            eventBus.$on('editMateriel', data => {
                this.materielId = data.materielId;
                this.materiel = data.materiel;
                this.types = data.types;
                this.materielModif = JSON.parse(JSON.stringify(data.materiel));
                this.materielTypeModif = JSON.parse(JSON.stringify(data.materielType.value));
            });

        },
        methods: {

            validateState(ref) {
                if (this.veeFields[ref] && (this.veeFields[ref].dirty || this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            // Clic sur Valider Edit Materiel
            handleOkModif(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.modifMateriel();
            },

            // Modification Materiel
            modifMateriel() {
                this.$validator.validateAll().then( result => {
                    if(!result)
                    {
                        return;
                    }
                    else
                    {
                        axios.put('/materiels/'+this.materielId,{
                            "constructeur": this.materielModif.constructeur,
                            "modele": this.materielModif.modele,
                            "type": this.materielTypeModif,
                            "nb_ex": this.materielModif.nb_ex,
                            "description": this.materielModif.description
                        })
                            .then( response => {
                                this.materiel = response.data.materiel;
                                this.materielType = { 'value': this.materiel.type.id, 'text': this.materiel.type.nom };
                                this.$nextTick( () =>  {
                                    this.$refs.modal1.hide();
                                });

                                eventBus.$emit('editedMateriel');

                                this.$bvToast.toast(`Matériel modifié avec succès !`, {
                                    title: `Modification réussie`,
                                    toaster: 'b-toaster-bottom-right',
                                    solid:true,
                                    variant:'success',
                                    appendToast: false
                                });

                                this.materielModif = [];
                                this.materielTypeModif = [];
                            })
                            .catch( error => {
                                console.log(error.response);
                            });
                    }
                })

            },

        }
    }
</script>