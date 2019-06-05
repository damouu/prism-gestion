<template>
    <div id="navigationReservationAgenda">
        <b-nav vertical>
            <b-nav-item @click="navEmp">Emprunts et retours</b-nav-item>
            <b-nav-item @click="navRes">Toutes les réservations</b-nav-item>
            <b-nav-item @click="navEx">Tous les exemplaires</b-nav-item>
        </b-nav>
    </div>
</template>

<script>

    import { eventBus } from "../../main";

    export default {
        name: 'NavigationReservationAgenda',
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {
            navEmp()
            {
                eventBus.$emit('navigation', {'navigation':'emprunts'});
            },
            navRes()
            {
                eventBus.$emit('navigation', {'navigation':'reservations'});
            },
            navEx()
            {
                eventBus.$emit('navigation', {'navigation':'exemplaires'});
            },
        }

    }
</script>