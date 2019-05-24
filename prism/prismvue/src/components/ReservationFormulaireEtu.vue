<template>
    <div id="ReservationFormulaireEtu">

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Reservation Etudiante</h1>
            </b-col>
        </b-row>

        <form ref="modifMateriel"@submit.stop.prevent="modifMateriel" >

            <b-row v-show="formulaire">
                <b-row>
                    <b-form-group label="Responsable projet" label-for="formEtuResponsable">
                        <b-form-input
                                id="formEtuResponsable"
                                list="listResponsable"
                                v-model="formEtu.responsable"
                                v-validate="{required:true}"
                                data-vv-name="responsable"
                                :state="validateState('responsable')"
                                aria-describedby="invalidResponsable"
                                placeholder="Nom du responsable"
                                type="text">
                        </b-form-input>
                        <b-form-datalist id="listResponsable" :options="responsables" @click="chosenResponsable"></b-form-datalist>
                        <b-form-invalid-feedback id="invalidResponsable">Veuillez choisir un Responsable</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Tel Responsable projet" label-for="formEtuResponsableTel">
                        <b-form-input
                                id="formEtuResponsableTel"
                                v-model="formEtu.responsableTel"
                                v-validate="{required:true}"
                                data-vv-name="responsableTel"
                                :state="validateState('responsableTel')"
                                aria-describedby="invalidResponsableTel"
                                placeholder="Téléphone du responsable"
                                type="text">
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidResponsableTel">Veuillez entrer un numero de telephone valide</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Mail Responsable projet" label-for="formEtuResponsableMail">
                        <b-form-input
                                id="formEtuResponsableMail"
                                v-model="formEtu.responsableMail"
                                v-validate="{required:true}"
                                data-vv-name="responsableMail"
                                :state="validateState('responsableMail')"
                                aria-describedby="invalidResponsable"
                                placeholder="mail du responsable"
                                type="text">
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidResponsableMail">Veuillez entrer un mail valide</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Département *" label-for="formEtuDepartement">
                        <b-form-select
                                id="formEtuDepartement"
                                v-model="formEtu.departement"
                                v-validate="{required:true}"
                                data-vv-name="departement"
                                :state="validateState('departement')"
                                aria-describedby="invalidDepartement">
                            <option v-for="option in departement" v-bind:value="option.value">
                                {{ option.text }}
                            </option>
                            <b-form-invalid-feedback id="invalidDepartemnt">Choisissez un département</b-form-invalid-feedback>
                        </b-form-select>
                    </b-form-group>
                    <b-col>
                        <b-button pill variant="outline-info"><strong>+</strong></b-button>
                    </b-col>

                    <b-form-group label="Matière" label-for="formEtuMatiere">
                        <b-form-input
                                id="formEtuMatiere"
                                v-model="formEtu.matiere"
                                v-validate="{required:true}"
                                data-vv-name="matiere"
                                :state="validateState('matiere')"
                                aria-describedby="invalidMatiere"
                                placeholder="Matière"
                                type="text">
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidMatiere">Veuillez choisir une matiere</b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Projet" label-for="formEtuProjet">
                        <b-form-input
                                id="formEtuProjet"
                                v-model="formEtu.projet"
                                v-validate="{required:true}"
                                data-vv-name="projet"
                                :state="validateState('projet')"
                                aria-describedby="invalidProjet"
                                placeholder="Projet"
                                type="text">
                        </b-form-input>
                        <b-form-invalid-feedback id="invalidProjet">Veuillez écrire le nom du projet</b-form-invalid-feedback>
                    </b-form-group>

                    <b-col cols="12">
                        <b-button variant="outline-success"  @click="next">Passer à la réservation</b-button>
                    </b-col>
                </b-row>

                <b-row>

                </b-row>
            </b-row>

            <b-row v-show="!formulaire">
                <b-button variant="outline-secondary" @click="back">Revenir en arrière</b-button>
                <b-button variant="success" @click="validate">Valider</b-button>
            </b-row>

        </form>


    </div>
</template>


<script>


    export default {
        name: 'ReservationFormulaireEtu',
        data() {
            return {
                formulaire: true,
                formEtu: [],

                departement: [],
                departements: [],
                responsables: [],
            }
        },
        mounted() {
            this.getDepartements();
            this.getResponsables();
        },
        methods: {
            next(){
                this.formulaire = false;
            },
            back(){
                this.formulaire = true;
            },

            validateState(ref) {
                if (this.veeFields[ref]&&(this.veeFields[ref].dirty||this.veeFields[ref].validated)) {
                    return !this.errors.has(ref);
                }
                return null;
            },

            validate()
            {
                console.log('c\'est envoyé');
            },


            getDepartements(){
                axios.get('/departements')
                    .then( response => {
                        this.departements = response.data.departements;
                        this.departements.forEach(element => {
                            this.departement.push({'value':element.id, 'text': element.nom});
                        });
                    })
                    .catch(error => {
                        console.log(error.response);
                    })
            },

            getResponsables() {
                axios.get('/professeurs')
                    .then( response => {
                        response.data.professeurs.forEach( element => {
                            this.responsables.push(element.nom+" "+element.prenom);
                        });
                    })
                    .catch( error => {
                        console.log(error.response);
                    })
            },

            chosenResponsable(){
                console.log(this.formEtu.responsable);
            }

        }

    }
</script>