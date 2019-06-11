<template>
    <div id="AgendaEmprunts">

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Agenda - Emprunts et Retours <b-spinner v-if="loading" class="loading" label="loading"></b-spinner></h1>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="12">
                <FullCalendar
                        :events="calendarEvents"
                        themeSystem="bootstrap"
                        :plugins="plugins"
                        :event-limit="'true'"
                        :locale="'fr'"
                        height="auto"
                        min-time="07:30:00"
                        max-time="19:00:00"
                        :header="{
                            left: ' prev,today,next',
                            center:'title',
                            right:'dayGridMonth timeGridWeek timeGridDay'
                        }"
                        :button-text="config"
                        bootstrap-font-awesome="false"
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
        name:"AgendaEmprunts",
        components: {
            FullCalendar,
        },
        data() {
            return {
                calendarEvents: [],
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
                loading:false,
            }
        },
        mounted(){

            this.getEventsEmp();

        },
        methods: {
            getEventsEmp(){
                this.loading=true;
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
                        this.loading=false;
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