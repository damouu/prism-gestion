<template>
    <div id="navigationReservation">
        <b-nav vertical>
            <b-nav-item @click="navEtu">Reservation etudiante</b-nav-item>
            <b-nav-item @click="navProf">Reservation autre</b-nav-item>
            <b-nav-item @click="navResa">Consulter les réservations</b-nav-item>
            <b-nav-item @click="navFiches">Consulter les fiches reservation</b-nav-item>
        </b-nav>
    </div>
</template>

<script>

    import { eventBus } from "../../main";

    export default {
        name: 'NavigationReservation',
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {
            navEtu()
            {
                eventBus.$emit('navigation', {'navigation':'etudiant'});
            },
            navProf()
            {
                eventBus.$emit('navigation', {'navigation':'professeur'});
            },
            navResa() {
                eventBus.$emit('navigation', {'navigation': 'reservations'});
            },
            navFiches(){
                eventBus.$emit('navigation', { 'navigation': 'fiches'});
            }
        }

    }
</script>