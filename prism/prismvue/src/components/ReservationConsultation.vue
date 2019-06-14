<template>
    <div id="ReservationConsultation">
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

        <b-row align-h="between" class="mb-4">
            <b-col>
                <h1 class="ml-5">Consultation réservations</h1>
            </b-col>
        </b-row>

        <b-table
                striped hover
                :items="reservations"

                :fields="fieldsRow"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :filter="filter"

                show-empty

                selectable
                :select-mode="mode"
                :per-page="perPage"
                :current-page="currentPage"
                selectedVariant="success"
                @row-selected="rowSelected">
            <template slot="empty" slot-scope="slot">
                <h6 class="text-center">Pas de réservations.</h6>
            </template>
        </b-table>
        <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
        ></b-pagination>

    </div>
</template>

<script>
    export default {
        name:"ReservationConsultation",
        data(){
            return {
                reservations: [],


                mode: 'single',
                selected: [],
                sortBy : 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'responsable_projet', sortable:true},
                    { key: 'departement', sortable:true },
                    { key: 'matiere', sortable:true },
                    { key: 'annee', sortable:true },
                    { key: 'dep_groupe', sortable:true },
                    { key: 'created_at', sortable:true, label:'Date Creation' }
                ],
                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 30],
                filter: null,

                alert: {'show':false,'showMateriel':false},
                dismissCountDown:0,
                dismissSecs:10,
                loading: false,

            }
        },
        mounted(){
            this.getReservations();
        },
        computed: {
            rows() {
                return this.reservations.length
            },
        },
        methods:{
            getReservations(){
                axios.get('/reservations')
                    .then(response => {
                        this.reservations=[];
                        response.data.reservations.forEach(elements => {
                            this.reservations.push({
                                id:elements.id,
                                responsable_projet:elements.professeur.nom+' '+elements.professeur.prenom,
                                departement: elements.departement.nom,
                                matiere: elements.matiere,
                                annee: elements.annee,
                                dep_groupe: elements.dep_groupe,
                                created_at: elements.created_at
                            })
                        });
                    })
                    .catch(error => {

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

            rowSelected(items) {
                this.selected = items;
                let idSelected = items[0];
                this.$router.push({name: 'consultation_reservation', params: { id: idSelected.id }});
            },


        }

    }
</script>