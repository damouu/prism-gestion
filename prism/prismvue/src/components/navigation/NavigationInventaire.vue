<template>
    <div id="navigationInventaire">
        <b-nav vertical>
            <b-nav-item to="/inventaire">Inventaire</b-nav-item>
            <b-nav-item to="/inventaire/fournisseurs">Fournisseurs</b-nav-item>
            <b-nav-item to="/inventaire/reformes">Exemplaires réformés</b-nav-item>
            <b-nav-item to="/inventaire/categories">Catégories</b-nav-item>
        </b-nav>
    </div>
</template>

<script>


    export default {
        name: 'NavigationInventaire',
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