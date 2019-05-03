<template>
    <div>

        <b-modal
                id="modal-DelExemplaire"
                title="Suppression d'exemplaire"
                ref="modal4"
                scrollable
                centered
                size="lg"
                @ok="handleOkDel"
        >
            <b-form ref="delExemplaire">
                <p>ahhahhahh</p>
            </b-form>

        </b-modal>

    </div>
</template>

<script>

    import { eventBus } from '../main';

    export default {
        name: 'ModalDeleteExemplaire',
        data() {
            return {

            }
        },
        mounted() {
            eventBus.$on('delMateriel', data => {

            });
        },
        methods: {

            handleOkDel(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.deleteMateriel();
            },

            deleteMateriel() {

            }

        }
    }
</script>