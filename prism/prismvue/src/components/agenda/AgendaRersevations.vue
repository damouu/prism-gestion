<template>
    <div id="AgendaReservation">

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Agenda - Réservations</h1>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="12">
                <FullCalendar
                        defaultView="dayGridMonth"
                        :events="calendarEvents"
                        themeSystem="bootstrap"
                        :plugins="plugins"
                        :event-limit="'true'"
                        :locale="'fr'"
                        min-time="07:30:00"
                        max-time="19:00:00"
                        :header="{
                            left: ' prev,today,next',
                            center:'title',
                            right:'dayGridMonth customWeek'
                        }"
                        :button-text="config"
                        bootstrap-font-awesome="false"
                        height="auto"
                        :views="views"
                        @eventClick="handleDateClick"
                />
            </b-col>
        </b-row>
    </div>
</template>
<script>


    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import bootstrapPlugin from '@fullcalendar/bootstrap';
    import timeGridPlugin from '@fullcalendar/timegrid';
    import timeline from '@fullcalendar/timeline';

    export default {
        name:"AgendaReservation",
        components: {
            FullCalendar,
        },
        data() {
            return {
                calendarEvents: [],
                colors: [
                    'red',
                    'pink',
                    'purple',
                    'blue',
                    'indigo',
                    'deepPurple',
                    'lightBlue',
                    'teal',
                    'green',
                    'lightGreen',
                    'lime',
                    'amber',
                    'orange',
                    'deepOrange',
                    'brown',
                    'grey',
                    'blueGrey'
                ],
                plugins: [
                    bootstrapPlugin, dayGridPlugin, timeGridPlugin, timeline
                ],
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
                views: {
                    customWeek:{ type: 'timeline', duration:{weeks:1}, slotDuration:{days:1}, buttonText:'customWeek'},
                    customDay:{type:'timeGridDay', titleFormat:{ day:'numeric', weekday:'long', month:'short', year:'numeric'}, columnHeaderHtml:{weekday:'long'} , duration:{days:1}, slotDuration:{days:1}, buttonText:'customDay'},
                },
            }
        },
        mounted(){

            this.getEventsResa();

        },
        methods: {
            getEventsResa(){
                axios.get('/agenda')
                    .then( response => {
                        response.data.agenda.forEach(element => {
                            let color = this.colors[Math.floor(Math.random() * this.colors.length)];
                            this.calendarEvents.push({title: element.reservation, start: element.date_depart, end:element.date_retour, id: element.id, color: color})
                        })

                    })
                    .catch( error => {
                        console.log(error);
                    })
            },
            handleDateClick(arg) {
                console.log(arg.event);
                console.log(arg.el);
                console.log(arg.jsEvent)
            },
        }

    }
</script>