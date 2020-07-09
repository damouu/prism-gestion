<template>
    <div id="AgendaExemplaires">

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Agenda - Exemplaires
                    <b-spinner v-if="loading" class="loading" label="loading"></b-spinner>
                </h1>
            </b-col>
        </b-row>

        <b-tabs pills align="center">
            <b-tab v-if='currentType == "Tous"' title="Tous" active>
            </b-tab>
            <b-tab @click="setCurrentType('Tous')" v-else title="Tous">
            </b-tab>
            <template v-for="type in types">
                <b-tab v-if='currentType == type.id' :title="type.nom" active>
                </b-tab>
                <b-tab v-else @click="setCurrentType(type.id)" :title="type.nom">
                </b-tab>
            </template>
        </b-tabs>

        <b-row>
            <b-col cols="12" class="mt-4">
                <FullCalendar
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
        name: "AgendaExemplaires",
        components: {
            FullCalendar,
        },
        data() {
            return {
                types: [],
                currentType: 'Tous',
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
                views: {
                    customWeek: {
                        type: 'timeline',
                        duration: {weeks: 1},
                        slotDuration: {days: 1},
                        buttonText: 'customWeek'
                    },
                    customDay: {
                        type: 'timeGridDay',
                        titleFormat: {day: 'numeric', weekday: 'long', month: 'short', year: 'numeric'},
                        columnHeaderHtml: {weekday: 'long'},
                        duration: {days: 1},
                        slotDuration: {days: 1},
                        buttonText: 'customDay'
                    },
                },
                loading: false,
            }
        },
        mounted() {

            this.getEventsEx();

        },
        methods: {
            getEventsEx() {
                this.loading = true;
                this.getTypes();
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/exemplaires?select=reservation&?query=' + this.currentType)
                    .then(response => {
                        response.data.reservations.forEach(element => {
                            element.exemplaires.forEach(q => {
                                this.calendarEvents.push({
                                    title: q.reference,
                                    start: element.date_depart,
                                    end: element.date_retour,
                                    id: q.id,
                                    type: q.materiel.type.nom,
                                    color: q.materiel.type.couleur
                                })
                            })
                        });
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },

            handleDateClick(arg) {
                console.log(arg.event);
                console.log(arg.el);
                console.log(arg.jsEvent)
            },

            getTypes() {
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/types/')
                    .then(response => {
                        this.types = response.data.types;
                    })
                    .catch(function (error) {
                        this.showAlert(error.response.statusText, error.response.status, error.response.data.message);
                    });
            },
            setCurrentType(type) {
                this.currentType = type;
                this.calendarEvents = [];
                this.getEventsEx();
            },

        }

    }
</script>