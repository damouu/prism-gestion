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

                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Agenda - {{agenda}}</h1>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12">
                                <FullCalendar
                                        defaultView="customWeek"
                                        :events="calendarEvents"
                                        themeSystem="bootstrap"
                                        :plugins="plugins"
                                        :event-limit="'true'"
                                        :locale="'fr'"
                                        min-time="07:00:00"
                                        max-time="19:00:00"
                                        :header="{
                            left: ' prev,today,next',
                            center:'title',
                            right:'dayGridMonth customWeek timeGridDay'
                        }"
                                        :button-text="config"
                                        bootstrap-font-awesome="false"
                                        :views="views"
                                        @eventClick="handleDateClick"
                                />
                            </b-col>
                        </b-row>
                    </b-col>

                </b-row>
            </b-container>
        </div>
    </div>
</template>


<script>


    import {Calendar} from '@fullcalendar/core';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import bootstrapPlugin from '@fullcalendar/bootstrap';
    import timeGridPlugin from '@fullcalendar/timegrid';
    import timeline from '@fullcalendar/timeline';
    import NavigationReservationAgenda from "../components/navigation/NavigationReservationAgenda";
    import {eventBus} from "../main";

    export default {
        name: "agenda",
        components: {
            NavigationReservationAgenda,
            FullCalendar,
        },
        data(){
            return {
                agenda: 'reservations',
                config:
                    {
                        today: 'aujourd\'hui',
                        month: "mois",
                        week: 'semaine',
                        day: 'jour',
                        list: 'liste',
                        prev: 'précédent',
                        next: 'suivant',
                    },
                theming:
                    {
                        prev: 'fa-angle-left'
                    },
                views: {
                    customWeek:{ type: 'timeline', duration:{weeks:1}, slotDuration:{days:1}, buttonText:'customWeek'},
                    customDay:{type:'timeGridDay', titleFormat:{ day:'numeric', weekday:'long', month:'short', year:'numeric'}, columnHeaderHtml:{weekday:'long'} , duration:{days:1}, slotDuration:{days:1}, buttonText:'customDay'}
                },

                plugins: [
                    bootstrapPlugin, dayGridPlugin, timeGridPlugin, timeline
                ],
                calendarEvents: [],

                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,
            }
        },
        mounted() {
            this.getEventsResa();

            eventBus.$on('navigation', data => {
                this.agenda = data.navigation;
                if(this.agenda==='reservations')
                {
                    this.calendarEvents= [];
                    this.getEventsResa();
                }
                if(this.agenda==='exemplaires')
                {
                    this.calendarEvents= [];
                    this.getEventsEx();
                }
                if(this.agenda==='emprunts')
                {
                    this.calendarEvents= [];
                    this.getEventsEmp();
                }

            });
        },
        methods: {

            handleDateClick(arg) {
                console.log(arg.event);
                console.log(arg.el);
                console.log(arg.jsEvent)
            },
            getEventsResa(){
                axios.get('/agenda')
                    .then( response => {
                        response.data.agenda.forEach(element => {
                            this.calendarEvents.push({title: element.reservation, start: element.date_depart, end:element.date_retour, id: element.id})
                        })

                    })
                    .catch( error => {
                        console.log(error);
                    })
            },

            getEventsEx(){
                axios.get('/agenda')
                    .then( response => {
                            response.data.agenda.forEach(element => {
                                element.exemplaire.forEach( q => {
                                    this.calendarEvents.push({title: q.reference, start: element.date_depart, end:element.date_retour, id: q.id})
                                })
                            })
                        })
                    .catch( error => {
                        console.log(error);
                    })
            },

            getEventsEmp(){
                axios.get('/agenda')
                    .then( response => {
                        response.data.agenda.forEach(element => {
                            let end = new Date(element.date_depart);
                            end.setHours(end.getHours()+1);
                            let ended = new Date(element.date_retour);
                            ended.setHours(end.getHours()+1);
                            this.calendarEvents.push({title: 'reservation n°'+element.reservation, start: element.date_depart, end:end, id: element.id, color:"green"});
                            this.calendarEvents.push({title: 'reservation n°'+element.reservation, start:element.date_retour, end:ended, id:element.id, color:"red"});
                        })
                    })
                    .catch( error => {
                        console.log(error);
                    })
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
            },
            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },

            getOneEvent(){

            }

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