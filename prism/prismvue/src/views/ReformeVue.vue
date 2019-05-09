<template>
    <div id="reforme">
        <div>

            <b-container>
                <b-alert
                        :show="dismissCountDown"
                        variant="danger"
                        dismissible
                        @dismissed="dismissCountDown=0"
                        @dismiss-count-down="countDownChanged"
                >

                    <p class="text-center">{{ alert.status }} {{ alert.error }}: {{ alert.message }}</p>
                    <p class="text-right">Cette alerte se fermera dans {{ dismissCountDown }} secondes.</p>
                </b-alert>
            </b-container>

            <b-container fluid class="mt-5 ">

                <b-row class="mr-5 ml-5">


                    <b-col cols="2">
                        <NavigationInventaire />
                    </b-col>

                    <b-col cols="10">

                        <b-row>
                            <b-button variant="outline-secondary" @click="backReformes">‹ retour</b-button>
                            <h1 class="ml-5">Exemplaire réformé</h1>
                        </b-row>


                        <b-row align-h="between" class="mt-4">
                            <h3>Informations</h3>
                            <b-col cols="8">
                                <b-button variant="primary" class="mr-2" >Modifier</b-button>
                                <b-button variant="outline-danger" class=" ml-2">Immobilisation</b-button>
                            </b-col>
                        </b-row>

                        <b-row align-h="around" class="mt-4">
                            <b-col cols="6">

                                <b-card>
                                    <b-row align-h="between" class="my-1">
                                        <b-col sm="4" class="mt-1">
                                            <label for="referenceReforme">Reference</label>
                                        </b-col>
                                        <b-col sm="7" class="mt-1">
                                            <p id="referenceReforme">{{reforme.reference}}</p>
                                        </b-col>
                                    </b-row>
                                </b-card>
                            </b-col>
                        </b-row>

                    </b-col>
                </b-row>
            </b-container>

        </div>
    </div>
</template>


<script>

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    export default {
        name:"reforme",
        components: {NavigationInventaire},
        component: {

        },
        data () {
            return {
                reformeId: this.$route.params.id,
                reforme: [],

                dismissCountDown:0,
                dismissSecs:10,
                alert: [],

            }
        },
        mounted () {
            this.getReformeId();
        },
        methods: {

            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },

            backReformes() {
                this.$router.push({path: '/inventaire'});
            },

            getReformeId() {
                axios.get('/exemplaires/'+this.reformeId)
                    .then( response => {
                        this.reforme = response.data.exemplaire;
                    })
                    .catch( error => {

                    });
                console.log(this.reforme);
            }


        }
    }

</script>