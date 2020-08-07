<template>
    <div id="reservation">
        <div>
            <b-container>
                <b-alert
                        :show="dismissCountDown"
                        variant="danger"
                        dismissible
                        @dismissed="dismissCountDown=0"
                        @dismiss-count-down="countDownChanged">
                    <p class="text-center">{{ alert.status }} {{ alert.error }}: {{ alert.message }}</p>
                    <p class="text-right">Cette alerte se fermera dans {{ dismissCountDown }} secondes.</p>
                </b-alert>
            </b-container>

            <b-container fluid class="mt-5 ">

                <b-row class="mr-5 ml-5">
                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5"></h1>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col cols="6">
                        <h1>Reservations à venir</h1>
                        <b-table>
                        </b-table>
                    </b-col>
                    <b-col cols="6">
                        <h1>Emprunts en cours</h1>
                        <b-table>
                        </b-table>
                    </b-col>
                </b-row>
            </b-container>
        </div>
    </div>
</template>

<script>
<<<<<<< Updated upstream
    export default {
        name: "reservation",
        data() {
            return {
                alert: {'show': false, 'showMateriel': false},
                dismissCountDown: 0,
                dismissSecs: 10,
            }
        },
        mounted() {
            this.userSignIn();
            this.userJWT();
        },
        beforeMount() {
            this.$store.commit('uNetID', document.getElementById("uNetID").innerHTML);
            document.getElementById('uNetID').remove();
        },
        methods: {
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown;
=======
export default {
  name: "reservation",
  data() {
    return {
      reservations: [],
      emprunts: [],
      informationsFicheResa: [],
      materiels: [],
      mode: 'single',
      sortByReservation: 'created_at',
      sortByEmprunts: 'date_depart',
      sortDesc: true,
      fieldsRowReservation: [
        {key: 'responsable_projet', sortable: true},
        {key: 'departement', sortable: true},
        {key: 'matiere', sortable: true},
        {key: 'annee', sortable: true},
        {key: 'dep_groupe', sortable: true},
        {key: 'created_at', sortable: true, label: 'Date Creation'}
      ],
      fieldsRowEmprunts: [
        {key: 'constructeur', sortable: true},
        {key: 'modele', sortable: true},
        {key: 'exemplaire', sortable: true},
        {key: 'date_depart', sortable: true},
        {key: 'date_retour', sortable: true},
      ],
      perPage: 5,
      alert: {'show': false, 'showMateriel': false},
      dismissCountDown: 0,
      dismissSecs: 10,
    }
  },
  mounted() {
    this.userJWT();
    this.getReservations();
    this.getEmprunts();
  },
  beforeMount() {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwczpcL1wvaXV0bmMtcmVzYW1hdC51bml2LWxvcnJhaW5lLmZyIiwiYXVkIjoiaHR0cHM6XC9cL2l1dG5jLXJlc2FtYXQudW5pdi1sb3JyYWluZS5mciIsImlhdCI6MTU5Njc0NTgwMiwiZXhwIjoxNTk2NzQ5NDAyLCJ1TmV0SUQiOiJzZWhiYW91aTF1IiwibHZsIjozfQ.CTY9uj0u1NLZEAgnMduxkQvE34bDl8raIoRvnA5_clHS5tTOgUYrlyL_LuXOAbgv_VgJsE_Z6FedAOhjPzBMEQ' /*+ document.getElementById("JWT").innerHTML*/
    document.getElementById("JWT").remove();
  },
  methods: {
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
>>>>>>> Stashed changes

            },
            showAlert(error, status, message) {
                this.alert.error = error;
                this.alert.status = status;
                this.alert.message = message;
                this.dismissCountDown = this.dismissSecs;
            },
            userSignIn() {
                window.axios.post("user/signIn?NetID=" + this.$store.getters.uNetID).then(response => this.$store.commit("JWT", response.data.JWT));
            },
            userJWT() {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.getters.JWT
                window.axios.post("JWT/").then(response => this.$store.commit('uNetLvl', response.data.data.lvl))
            }
        },
    }
</script>