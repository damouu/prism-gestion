<template>
    <div id="Agenda">

        ceci est l'agenda

        <b-row>
            <b-col cols="12">
                <FullCalendar
                        defaultView="customWeek"
                        :events="calendarEvents"
                        themeSystem="bootstrap"
                        :plugins="plugins"
                        event-limit="false"
                        :locale="'fr'"
                        :header="{
                            left: ' prev,today,next',
                            center:'title',
                            right:'dayGridMonth customWeek customDay'
                        }"
                        :button-text="config"
                        bootstrap-font-awesome="false"
                        :views="views"
                        @eventClick="handleDateClick"
                />
            </b-col>

        </b-row>



    </div>

</template>

<script>


    import {Calendar} from '@fullcalendar/core';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import bootstrapPlugin from '@fullcalendar/bootstrap';
    import timeGridPlugin from '@fullcalendar/timegrid';
    import timeline from '@fullcalendar/timeline';

    export default {
        name: "agenda",
        components: {
            FullCalendar,
        },
        data(){
            return {
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
                    customDay:{type:'timeline', titleFormat:{ day:'numeric', weekday:'long', month:'short', year:'numeric'}, columnHeaderHtml:{weekday:'long'} , duration:{days:1}, slotDuration:{days:1}, buttonText:'customDay'}
                },

                plugins: [
                    bootstrapPlugin, dayGridPlugin, timeGridPlugin, timeline
                ],
                calendarEvents: [
                    {title: 'Event Now2', start: new Date(), end: new Date('June 5, 2019 08:30:00'), color: 'yellow', id:6 },
                    {title: 'Event Now1', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now4', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now3', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                ]
            }
        },
        mounted() {

        },
        methods: {

            handleDateClick(arg) {
                console.log(arg.event);
                console.log(arg.el);
                console.log(arg.jsEvent)
            },
            getEvents(){
                axios.get()
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