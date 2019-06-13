<template>
    <div id="reservations">
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

                    <b-col cols="2">
                        <NavigationReservation/>
                    </b-col>

                    <b-col cols="10">

                        <div v-if="navigation === 'etudiant'">
                            <ReservationFormulaireEtu />
                        </div>

                        <div v-else-if="navigation === 'professeur'">
                            <ReservationFormulaireProf />
                        </div>

                        <div v-else-if="navigation === 'reservations'">
                            3
                        </div>
                    </b-col>
                </b-row>
            </b-container>
        </div>
    </div>
</template>

<script>
    import NavigationReservation from '../components/navigation/NavigationReservation';
    import ReservationFormulaireEtu from '../components/ReservationFormulaireEtu';
    import ReservationFormulaireProf from '../components/ReservationFormulaireProf';
    import { eventBus } from "../main";

    export default {
        name:"reservation",
        components:{
            NavigationReservation,
            ReservationFormulaireEtu,
            ReservationFormulaireProf,
        },
        data() {
            return {
                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,

                navigation: 'reservations',
            }
        },
        mounted() {
            eventBus.$on('navigation', data => {
                this.navigation = data.navigation;
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