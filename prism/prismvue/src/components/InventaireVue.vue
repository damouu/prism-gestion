<template>
    <div id="inventaire">

        <div>

            <b-container class="mt-5">

                <h1 class="ml-5">Inventaire</h1>
                <b-tabs pills align="center">
                    <b-tab title="Tous" active>
                        <b-table hover :items="all" class="mt-5"></b-table>
                    </b-tab>
                    <template v-for="typesTitle in typesAll" >
                        <b-tab :title="typesTitle">
                            <b-table hover :items="types" class="mt-5"></b-table>
                        </b-tab>
                    </template>
                </b-tabs>


            </b-container>
        </div>




    </div>



</template>



<script>

    export default {
        name: 'Home',
        data : function () {
            return {

                materiels: [],
                all: [],
                types: [],
                typesAll: []
            }
        },
        mounted() {
            let tempThis = this;

            // API -  GET ALL MATERIELS
            this.getAll(this);

            // API - GET TYPES
            this.getTypes(this);

            // API - Remplissage des types par les materiels
            this.fillDynamic(this);



        },
        methods : {
            getAll(tempThis) {
                axios.get('/materiels')
                    .then(function (response) {
                        for (let data of response.data.materiels) {
                            data.type = data.type.nom;
                            tempThis.all.push(data);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getTypes(tempThis) {
                axios.get('/types')
                    .then(function (response) {
                        for (let data of response.data.types) {
                            tempThis.typesAll.push(data.nom);

                            axios.get('/materiels?types='+data.nom)
                                .then(function (resp) {
                                    tempThis.types.push(new Array(data.nom, resp.data.materiels));
                                })
                                .catch(function (error){
                                    tempThis.types.push(new Array(data.nom, 'empty'));
                                });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                console.log(tempThis.types);
            },
            fillDynamic(tempThis) {



                tempThis.typesAll.forEach(function(element) {
                    console.log('ARG');
                });

            },
        }
    }

</script>