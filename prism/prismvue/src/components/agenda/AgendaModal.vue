<style type="text/css">
    .modal-xl {
        min-height: 1000px;
    }
</style>

<template>
    <div id="AgendaModal">
        <b-modal
                id="modal-AgendaModal"
                title="Agenda"
                ref="modal15"
                scrollable
                size="xl">

            <FullCalendar
                    ref="test"
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
                    :views="views"
            />

        </b-modal>
    </div>
</template>


<script>
    import {eventBus} from "../../main";
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import bootstrapPlugin from '@fullcalendar/bootstrap';
    import timeGridPlugin from '@fullcalendar/timegrid';
    import timeline from '@fullcalendar/timeline';

    export default {
        name: "AgendaModal",
        components: {
            FullCalendar,
        },
        data() {
            return {
                fillAgenda: [],

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
            eventBus.$on('agenda', data => {
                if (typeof data.item.constructeur !== 'undefined') {
                    data.item.exemplaire.forEach(elements => {
                        elements.fiche_resa.forEach(q => {
                            this.calendarEvents.push({
                                title: elements.reference,
                                start: q.date_depart,
                                end: q.date_retour,
                                id: q.id
                            });
                        });

                    });
                } else {

                    data.item.forEach(elements => {
                        elements.fiche_resa.forEach(q => {
                            this.calendarEvents.push({
                                title: elements.reference,
                                start: q.date_depart,
                                end: q.date_retour,
                                id: q.id
                            });
                        })

                    });
                }
            });

        },
        methods: {}
    }
</script>