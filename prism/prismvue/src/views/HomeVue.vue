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

                    <ul>TO DO LIST PRISM APP
                        <li>Modifier API: RESTFUL</li>
                        <li>Agenda: click -> info modal</li>
                        <li>Reservation: agenda + infos items</li>
                        <li>Page Emprunt</li>
                        <li>Page Home</li>
                        <li>loading screen everywhere</li>
                        <li>position erreur showAlert</li>
                        <li>Agenda select type: not working</li>

                    </ul>

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
        name:"reservation",
        data() {
            return {
                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,
            }
        },
        mounted() {

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
        }
    }
</script>