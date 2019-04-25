<template>
    <div id="materiel">
        <div>
            {{materiel}}
        </div>
    </div>
</template>



<script>

    export default {
        name: 'MaterielVue',
        data() {
            return {
                materiel: this.$route.params.id
            }
        },
        mounted() {

        },
        methods : {

        }
    }

</script>