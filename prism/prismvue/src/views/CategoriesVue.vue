<template>
    <div id="categories">
        <div>
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

            <b-container fluid class="mt-5 ">

                <b-row class="mr-5 ml-5">

                    <b-col cols="2">
                        <NavigationInventaire />
                    </b-col>

                    <b-col cols="10">
                        <b-row align-h="between" class="mb-4">
                            <b-col>
                                <h1 class="ml-5">Catégories</h1>
                            </b-col>
                            <b-col cols="7">
                                <b-button variant="success" class="mr-2 ml-2" v-b-modal.modal-AddCategorie>Ajouter une catégorie</b-button>
                            </b-col>
                        </b-row>
                        <b-table
                                striped hover
                                :items="categories"

                                :fields="fieldsRow"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"

                                show-empty

                                :per-page="perPage"
                                :current-page="currentPage"
                                selectedVariant="success">
                            <template slot="empty" slot-scope="scope">
                                <h6 class="text-center">Pas de catégories à afficher.</h6>
                            </template>
                            <template slot="couleur" slot-scope="row">
                                <h5><b-badge v-bind:style="{ 'background-color':row.value}">{{row.value}}</b-badge></h5>
                            </template>
                            <template slot="actions" slot-scope="row">
                                <b-button size="sm" class="mr-1" variant="outline-danger" v-b-modal.modal-DeleteCategorie @click="modalDeleteCategorie(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                <b-button size="sm" class="mr-1" variant="outline-success" @click="modalDeleteCategorie(row.item)"><font-awesome-icon :icon="['fas','edit']" /></b-button>
                            </template>
                        </b-table>
                        <b-pagination
                                v-model="currentPage"
                                :total-rows="rows"
                                :per-page="perPage"
                        ></b-pagination>
                    </b-col>
                </b-row>
            </b-container>

            <ModalAddCategorie />
            <ModalDeleteCategorie />

        </div>

    </div>
</template>

<script>

    import { eventBus } from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalAddCategorie from '../components/modals/ModalAddCategorie';
    import ModalDeleteCategorie from "../components/modals/ModalDeleteCategorie";

    export default {
        name: "categories",
        components:{
            NavigationInventaire,
            ModalAddCategorie,
            ModalDeleteCategorie,
        },
        data(){
            return {
                categories: [],

                sortBy : 'id',
                sortDesc: false,
                fieldsRow: [
                    { key: 'nom', sortable:true },
                    { key: 'couleur', sortable:true },
                    { key: 'actions', sortable:true },
                ],
                currentPage: 1,
                perPage: 10,

                alert: [],
                dismissCountDown:0,
                dismissSecs:10,
            }
        },
        computed: {
            rows() {
                return this.categories.length
            }
        },
        mounted(){

            this.getTypes();
            eventBus.$on('error', data => {
                this.showAlert(data.error, data.status, data.message);
            });

            eventBus.$on('addedCategorie', data => {
                this.categories = [];
                this.getTypes();
            });
        },
        methods: {

            getTypes() {
                axios.get('/types')
                    .then(response => {
                        this.categories = [];
                        this.categories = response.data.types;
                    })
                    .catch(function (error) {
                        this.showAlert(error.response.statusText,error.response.status,error.response.data.message);
                    });
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
            modalDeleteCategorie(item) {
                eventBus.$emit('deleteCategorie',item);
            }

        }
    }

</script>