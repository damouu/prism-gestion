<template>
    <div id="agenda">
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
                        <NavigationReservationAgenda />
                    </b-col>

                    <b-col v-if="agenda === 'reservations'"  cols="10">
                        <AgendaReservation />
                    </b-col>

                    <b-col v-if="agenda === 'emprunts'"  cols="10">
                        <AgendaEmprunts />
                    </b-col>

                    <b-col v-if="agenda === 'exemplaires'"  cols="10">
                        <AgendaExemplaires />
                    </b-col>

                </b-row>
            </b-container>
        </div>
    </div>
</template>


<script>

    import NavigationReservationAgenda from "../components/navigation/NavigationReservationAgenda";
    import {eventBus} from "../main";
    import AgendaReservation from "../components/agenda/AgendaRersevation";
    import AgendaEmprunts from "../components/agenda/AgendaEmprunts";
    import AgendaExemplaires from "../components/agenda/AgendaExemplaires";

    export default {
        name: "agenda",
        components: {
            AgendaExemplaires,
            AgendaEmprunts,
            AgendaReservation,
            NavigationReservationAgenda
        },
        data(){
            return {
                agenda: 'reservations',
                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,
            }
        },
        mounted() {
            eventBus.$on('navigation', data => {
                this.agenda = data.navigation;
            });
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


<style lang="css">
    @import "~@fullcalendar/bootstrap/main.css";
    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/timegrid/main.css";
    @import "~@fullcalendar/daygrid/main.css";
    @import "~@fullcalendar/timegrid/main.css";
    @import "~@fullcalendar/timeline/main.css";
</style>