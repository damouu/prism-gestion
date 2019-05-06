<template>
    <div id="fournisseurs">
        <div>
            <b-container fluid class="mt-5">

                <b-row class="mr-5 ml-5">

                    <b-col cols="2">
                        <NavigationInventaire />
                    </b-col>

                    <b-col cols="10">
                    </b-col>
                </b-row>
            </b-container>
        </div>
    </div>
</template>

<script>

    import NavigationInventaire from '../components/navigation/NavigationInventaire'

    export default {
        name: 'fournisseurs',
        components: {
            NavigationInventaire,
        }
    }
</script>