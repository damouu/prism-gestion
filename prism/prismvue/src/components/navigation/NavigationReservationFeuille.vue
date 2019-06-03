<template>
    <div id="navigationReservationFeuille">
        <b-nav vertical>
            <b-nav-item @click="navResa">Consulter Réservation</b-nav-item>
            <b-nav-item @click="navFeuilles">Consulter feuilles réservations</b-nav-item>
            <b-nav-item @click="navAgenda">Consulter l'agenda</b-nav-item>
        </b-nav>
    </div>
</template>

<script>

    import { eventBus } from "../../main";

    export default {
        name: 'NavigationReservationFeuille',
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {
            navResa()
            {
                eventBus.$emit('navigation', {'navigation':'reservation'});
            },
            navFeuilles()
            {
                eventBus.$emit('navigation', {'navigation':'feuilles'});
            },
            navAgenda()
            {
                eventBus.$emit('navigation', {'navigation':'agenda'});
            }
        }

    }
</script>