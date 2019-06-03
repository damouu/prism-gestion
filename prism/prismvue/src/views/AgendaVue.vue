<template>
    <div id="Agenda">

        ceci est l'agenda

        <b-row>
            <b-col cols="12">
                <FullCalendar
                        defaultView="dayGridMonth"
                        :events="calendarEvents"
                        themeSystem="bootstrap"
                        :plugins="plugins"
                        event-limit="true"
                        :locale="'fr'"
                        :header="{
                            left: 'today prev,next',
                            center:'title',
                            right:'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        }"
                        :button-text="config"
                        bootstrap-font-awesome="false"
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
                        next: 'suivant'
                    },
                theming:
                    {
                        prev: 'fa-angle-left'
                    },

                plugins: [
                    bootstrapPlugin, dayGridPlugin, timeGridPlugin
                ],
                calendarEvents: [
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00'), color: 'yellow' },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
                    {title: 'Event Now', start: new Date(), end: new Date('June 5, 2019 08:30:00') },
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

        }
    }
</script>


<style lang="css">
    @import "~@fullcalendar/bootstrap/main.css";
    @import "~@fullcalendar/core/main.css";
    @import "~@fullcalendar/timegrid/main.css";
    @import "~@fullcalendar/daygrid/main.css";
    @import "~@fullcalendar/timegrid/main.css";
</style>