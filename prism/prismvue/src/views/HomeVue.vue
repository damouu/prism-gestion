<template>
    <div id="reservation">
        <div>
            <b-container>
                <b-alert
                        :show="dismissCountDown"
                        variant="danger"
                        dismissible
                        @dismissed="dismissCountDown=0"
                        @dismiss-count-down="countDownChanged">
                    <p class="text-center">{{ alert.status }} {{ alert.error }}: {{ alert.message }}</p>
                    <p class="text-right">Cette alerte se fermera dans {{ dismissCountDown }} secondes.</p>
                </b-alert>
            </b-container>

            <b-container fluid class="mt-5 ">

                <b-row class="mr-5 ml-5">
                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5"></h1>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col cols="6">
                        <h1>Reservations à venir</h1>
                        <b-table>
                        </b-table>
                    </b-col>
                    <b-col cols="6">
                        <h1>Emprunts en cours</h1>
                        <b-table>
                        </b-table>
                    </b-col>
                </b-row>
            </b-container>
        </div>
    </div>
</template>

<script>
    export default {
        name: "reservation",
        data() {
            return {
                alert: {'show': false, 'showMateriel': false},
                dismissCountDown: 0,
                dismissSecs: 10,
            }
        },
        mounted() {
            this.userSignIn();
            this.userJWT();
        },
        beforeMount() {
            this.$store.commit('uNetID', document.getElementById("uNetID").innerHTML);
            document.getElementById('uNetID').remove();
        },
        methods: {
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;

            },
            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },
            userSignIn() {
                window.axios.post("user/signIn?NetID=" + this.$store.getters.uNetID).then(response => this.$store.commit("JWT", response.data.JWT));
            },
            userJWT() {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.getters.JWT
                window.axios.post("JWT/").then(response => this.$store.commit('uNetLvl', response.data.data.lvl))
            }
        },
    }
</script>