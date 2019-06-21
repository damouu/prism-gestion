<template>
    <div id="navigationReservation">
        <b-nav vertical>
            <b-nav-item to="/reservation">Consulter les réservations</b-nav-item>
            <b-nav-item to="/reservation/feuilles">Consulter les fiches reservation</b-nav-item>
            <b-nav-item to="/reservation/etu">Reservation etudiante</b-nav-item>
            <b-nav-item to="/reservation/autre">Reservation autre</b-nav-item>
        </b-nav>
    </div>
</template>

<script>
    export default {
        name: 'NavigationReservation',
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {

        }

    }
</script>