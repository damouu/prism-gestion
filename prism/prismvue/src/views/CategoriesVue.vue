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

                        <div v-if="loading" class="loading text-center mt-5 mb-5">
                            <b-spinner label="loading" class="text-center"></b-spinner>
                            <h5>Chargement, veuillez patienter ... </h5>
                        </div>

                        <div v-else>

                            <b-row>

                                <b-col md="5" class="m-auto">
                                    <b-form-group label-cols-sm="3" label="Filtre" class="mb-0">
                                        <b-input-group>
                                            <b-form-input v-model="filter" placeholder="Recherche"></b-form-input>
                                            <b-input-group-append>
                                                <b-button :disabled="!filter" @click="filter = ''">Annuler</b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </b-form-group>
                                </b-col>

                                <b-col md="3" class="m-auto">
                                    <b-form-group label-cols-sm="3" label="Par page">
                                        <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
                                    </b-form-group>
                                </b-col>

                                <b-table
                                        striped hover
                                        :items="categories"

                                        :fields="fieldsRow"
                                        :sort-by.sync="sortBy"
                                        :sort-desc.sync="sortDesc"
                                        :filter="filter"

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
                                        <b-button size="sm" class="mr-1" variant="outline-danger" v-b-modal.modal-DelCategorie @click="modalDeleteCategorie(row.item)"><font-awesome-icon :icon="['fas','trash-alt']" /></b-button>
                                        <b-button size="sm" class="mr-1" variant="outline-success" v-b-modal.modal-EditCategorie @click="modalEditCategorie(row.item)"><font-awesome-icon :icon="['fas','edit']" /></b-button>
                                    </template>
                                </b-table>
                                <b-pagination
                                        v-model="currentPage"
                                        :total-rows="rows"
                                        :per-page="perPage"
                                ></b-pagination>
                            </b-row>

                        </div>

                    </b-col>
                </b-row>
            </b-container>

            <ModalAddCategorie />
            <ModalDeleteCategorie />
            <ModalEditCategorie />

        </div>

    </div>
</template>

<script>

    import { eventBus } from "../main";

    import NavigationInventaire from "../components/navigation/NavigationInventaire";
    import ModalAddCategorie from '../components/modals/ModalAddCategorie';
    import ModalDeleteCategorie from "../components/modals/ModalDeleteCategorie";
    import ModalEditCategorie from "../components/modals/ModalEditCategorie";

    export default {
        name: "categories",
        components:{
            ModalEditCategorie,
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
                pageOptions: [10, 20, 30],
                filter: null,

                alert: [],
                dismissCountDown:0,
                dismissSecs:10,
                loading:false,
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

            eventBus.$on('deleteSuccessCategorie', data => {
                this.categories = [];
                this.getTypes();
            });

            eventBus.$on('editedCategorie', data => {
                this.categories = [];
                this.getTypes();
            });
        },
        methods: {

            getTypes() {
                this.loading=true;
                axios.get('/types')
                    .then(response => {
                        this.categories = [];
                        this.categories = response.data.types;
                        this.loading=false;
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
            },
            modalEditCategorie(item) {
                eventBus.$emit('editCategorie',item);
            }
        }
    }

</script>