<template>
    <div id="reservations">
        <b-container fluid class="mt-5 ">

            <b-row class="mr-5 ml-5">

                <b-col cols="2">
                    <NavigationReservation/>
                </b-col>

                <b-col cols="10">

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

                    <div v-if="loading" class="loading text-center mt-5 mb-5">
                        <b-spinner label="loading" class="text-center"></b-spinner>
                        <h5>Chargement, veuillez patienter ... </h5>
                    </div>

                    <div v-else>
                        <b-row>
                            <b-col md="5" class="m-auto">
                                <b-form-group label-cols-sm="3" label="Filtre" class="mb-0">
                                    <b-input-group>
                                        <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                        <b-input-group-append>
                                            <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                        </b-input-group-append>
                                    </b-input-group>
                                </b-form-group>
                            </b-col>

                            <b-col md="3" class="m-auto">
                                <b-form-group label-cols-sm="3" label="Par page">
                                    <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                </b-form-group>
                            </b-col>
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
                        </b-row>
                    </div>

                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import NavigationReservation from '../components/navigation/NavigationReservation'

    export default {
        name: "reservation",
        components: {
            NavigationReservation
        },
        data() {
            return {
                reservations: [],


                mode: 'single',
                selected: [],
                sortBy: 'id',
                sortDesc: false,
                fieldsRow: [
                    {key: 'responsable_projet', sortable: true},
                    {key: 'departement', sortable: true},
                    {key: 'matiere', sortable: true},
                    {key: 'annee', sortable: true},
                    {key: 'dep_groupe', sortable: true},
                    {key: 'created_at', sortable: true, label: 'Date Creation'}
                ],
                currentPage: 1,
                perPage: 10,
                pageOptions: [10, 20, 30],
                filter: null,

                alert: {'show': false, 'showMateriel': false},
                dismissCountDown: 0,
                dismissSecs: 10,
                loading: false,

            }
        },
        mounted() {
            this.getReservations();
        },
        computed: {
            rows() {
                return this.reservations.length
            },
        },
        methods: {
            getReservations() {
                this.loading = true;
                axios.get('https://iutnc-resamat.univ-lorraine.fr/api/reservations/')
                    .then(response => {
                        this.reservations = [];
                        response.data.reservations.forEach(elements => {
                            this.reservations.push({
                                id: elements.id,
                                responsable_projet: elements.professeur.nom + ' ' + elements.professeur.prenom,
                                departement: elements.departement.nom,
                                matiere: elements.matiere,
                                annee: elements.annee,
                                dep_groupe: elements.dep_groupe,
                                created_at: elements.created_at
                            });
                        });
                        this.loading = false;
                    })
                    .catch(error => {
                        this.loading = false;
                        this.showAlert(error.response.statusText, error.response.status, error.response.data.message);
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
                this.$router.push({name: 'consultation_reservation', params: {id: idSelected.id}});
            },


        }

    }
</script>