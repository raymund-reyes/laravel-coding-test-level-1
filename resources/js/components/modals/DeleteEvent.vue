<template>
    <div>
        <b-modal id="delete-event" ref="delete-event" centered title="Event">

            <h3 class="text-center">Are you sure you want to delete this event?</h3>

            <template #modal-footer="{ ok, cancel }">
                <b-button size="sm" variant="danger" @click="cancel()">
                    <b-icon icon="x" />Cancel
                </b-button>
                <b-button size="sm" variant="primary" @click="submit()">
                    <b-icon icon="check" />Ok
                </b-button>
            </template>
        </b-modal>
    </div>

</template>
<script>
export default {
    data() {
        return {
            id: ''
        }
    },
    props: {
        eventInfo: Object
    },
    watch: {
        'eventInfo': function (val) {
            this.id = val.id
        }
    },
    methods: {
        submit() {
            let self = this
            axios
                    .delete(`/api/v1/events/${this.id}`,
                        {
                            headers: {
                                'accept': 'application/json'
                            }
                        })
                    .then((response) => {
                        if (response.data) {
                            this.$refs['delete-event'].hide()
                            this.$emit(
                                "showToast",
                                "Event Deleted",
                                response.data.message,
                                "success"
                            );
                            this.$emit('reloadPage')
                        } else {
                            this.error = response.data;
                            this.$emit(
                                "showToast",
                                "Event Delete Failed",
                                this.error,
                                "danger"
                            );
                        }

                    })
                    .catch(function (error) {
                        console.error(error);
                         self.$emit(
                            "showToast",
                            "Event Delete Failed",
                            "Event Delete Error. Please try again",
                            "danger"
                        );
                    });
        }
    }

}
</script>
