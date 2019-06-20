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

        <b-modal
                id="bv-modal-eventClick"
                hide-footer
                refs="bv-modal-eventClick"
        centered scrollable>
            <template slot="modal-header" slot-scope="">
                <h5>Information sur la feuille reservation n°<router-link :to="{path:'/reservation/'+modalClick.reservation}" target="_blank">{{modalClick.id}}</router-link></h5>
            </template>
            <div>
                <div>
                    <h4>Debut réservation: {{modalClick.debut_resa}}</h4>
                    <h4>Fin réservation: {{modalClick.fin_resa}}</h4>
                </div>
                <div>
                    <h4>Observations:</h4>
                    <p>{{modalClick.observation}}</p>
                </div>
                <div>
                    <h4>Exemplaires:</h4>
                    <b-table
                            :items="modalClick.materiels"
                            striped hover
                            :fields="fieldsMateriel"
                            show-empty
                    >
                        <template slot="detail" slot-scope="row">
                            <b-button variant="info" @click="row.toggleDetails" v-if="row.detailsShowing"><font-awesome-icon :icon="['fas','sort-down']" /></b-button>
                            <b-button variant="info" @click="row.toggleDetails" v-if="!row.detailsShowing"><font-awesome-icon :icon="['fas','caret-right']" /></b-button>
                        </template>
                        <template slot="row-details" slot-scope="row">
                            <b-card>
                                <b-table
                                        striped hover
                                        :fields="fieldsExemplaire"
                                        :items="row.item.exemplaires"
                                        show-empty
                                ></b-table>
                            </b-card>
                        </template>
                    </b-table>
                </div>



            </div>

        </b-modal>

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
                modalClick:[],

                fieldsMateriel: [
                    {key:'detail',sortable:false},
                    {key:'constructeur', sortable:true},
                    {key:'modele', sortable:true},
                ],
                fieldsExemplaire: [
                    {key:'reference', sortable:true},
                    {key:'num_ex', sortable:true},
                    {key:'num_serie', sortable:true},
                ],
            }
        },
        mounted(){

            this.getEventsEmp();

        },
        methods: {
            getEventsEmp(){
                this.loading=true;
                axios.get('/exemplaires?select=reservation')
                    .then( response => {
                        response.data.reservations.forEach(element => {
                            let end = new Date(element.date_depart);
                            end.setHours(end.getHours()+1);
                            let ended = new Date(element.date_retour);
                            ended.setHours(end.getHours()+1);

                            this.calendarEvents.push({
                                title: element.id,
                                start: element.date_depart,
                                end:end, color:"green",
                                otherDatas:{
                                    debut_resa:element.date_depart,
                                    fin_resa:element.date_retour,
                                    id: element.id, exemplaires:element.exemplaires,
                                    observation: element.observation,
                                    reservation:element.reservation
                                }
                            });

                            this.calendarEvents.push({title: element.id, start:element.date_retour, end:ended, color:"red", otherDatas:{debut_resa:element.date_depart,fin_resa:element.date_retour,id: element.id, exemplaires:element.exemplaires, observation: element.observation, reservation:element.reservation}});
                        })
                        this.loading=false;
                    })
                    .catch( error => {
                        console.log(error);
                    })
            },
            handleDateClick(arg) {

                this.modalClick= {
                    observation: arg.event._def.extendedProps.otherDatas.observation,
                    materiels: arg.event._def.extendedProps.otherDatas.exemplaires,
                    id: arg.event._def.extendedProps.otherDatas.id,
                    debut_resa:arg.event._def.extendedProps.otherDatas.debut_resa,
                    fin_resa:arg.event._def.extendedProps.otherDatas.fin_resa,
                    reservation:arg.event._def.extendedProps.otherDatas.reservation,
                };
                let materiel = [];
                this.modalClick.materiels.forEach(element => {
                    if(materiel!=undefined){
                        let exist = false;
                        materiel.forEach(test => {
                            if(test.id===element.materiel.id){
                                exist=true;
                            }
                        });
                        if(exist!==true){
                            materiel.push(element.materiel);
                        }
                    }
                });

                materiel.forEach(element=>{
                    element.exemplaires=[];
                    this.modalClick.materiels.forEach( value => {
                        if(value.materiel.id===element.id){
                            element.exemplaires.push({id:value.id,num_ex:value.num_ex, reference:value.reference, num_serie:value.num_serie});
                        }
                    })
                });

                this.modalClick.materiels = materiel;
                this.$bvModal.show('bv-modal-eventClick')
            },
        }

    }
</script>