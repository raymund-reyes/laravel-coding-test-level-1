<template>
    <div>
        <b-row class="justify-content-md-center mt-5">
            <b-col col lg="6">

                <div>
                    <b-card-group deck>
                        <b-card :title="(edit ? 'Edit' : 'View') +' Event'">
                            <b-form-group label="Event Name">
                                <b-form-input v-model="eventName" trim></b-form-input>
                            </b-form-group>
                            <b-form-group label="Event Slug">
                                <b-form-input v-model="eventSlug" trim></b-form-input>
                            </b-form-group>
                            <b-form-group label="Event Start">
                                <input type="datetime-local" class="form-control" v-model="startAt" />
                            </b-form-group>
                            <b-form-group label="Event End">
                                <input type="datetime-local" class="form-control" v-model="endAt" />
                            </b-form-group>
                            <b-button size="sm" variant="danger" @click="$router.push('/events')">
                                <b-icon icon="x" />Cancel
                            </b-button>
                            <b-button v-if="edit" size="sm" variant="primary" @click="submit()">
                                <b-icon icon="check" />Ok
                            </b-button>
                        </b-card>
                    </b-card-group>
                </div>
            </b-col>
        </b-row>

    </div>
</template>

<script>
export default {
    data() {
        return {
            eventName: '',
            eventSlug: '',
            startAt: '',
            endAt: '',
            id: '',
            edit: this.$route.params.edit == 'edit' ? true: false
        }
    },

    beforeMount() {
        this.getEventInfo()
    },
    methods: {
        getEventInfo() {
            var self = this
            axios
                .get(`/api/v1/events/${this.$route.params.id}`, {
                    headers: {
                        'accept': 'application/json'
                    }
                })
                .then((response) => {
                    let data = response.data[0]
                    this.eventName = data.name
                    this.eventSlug = data.slug
                    this.startAt = data.startAt
                    this.endAt = data.endAt
                    this.id = data.id
                })
                .catch(function (error) {
                    console.error(error);
                    self.loading = false
                });
        },
        submit() {

            if (this.eventName == ""
                || this.eventSlug == ""
                || this.startAt == ""
                || this.endAt == "") {
                this.showToast(
                    "Create Event",
                    "Please fill out all the fields to proceed",
                    "danger"
                );
                return;
            }

            let self = this
            axios
                    .put(`/api/v1/events/${this.id}`,
                        {
                            name:this.eventName,
                            slug: this.eventSlug,
                            start: this.startAt,
                            end: this.endAt
                        },
                        {
                            headers: {
                                'accept': 'application/json'
                            }
                        })
                    .then((response) => {
                        if (response.data) {
                            this.showToast(
                                "Event Updated",
                                response.data.message,
                                "success"
                            );
                            setTimeout(() => {
                                self.$router.push('/events')
                            }, 3000)
                        } else {
                            this.error = response.data;
                            this.showToast(
                                "Event Update Failed",
                                this.error,
                                "danger"
                            );
                        }

                    })
                    .catch(function (error) {
                        console.error(error);
                         self.showToast(
                            "Event Update Failed",
                            "Event Update Error. Please try again",
                            "danger"
                        );
                    });
        },
        showToast(
            title = "",
            message = "",
            variant = "default",
            position = "b-toaster-top-right"
        ) {

            const h = this.$createElement;
            const id = `my-toast-${this.toastCount++}`;
            const $closeButton = h(
                "b-button",
                {
                    on: { click: () => this.$bvToast.hide(id) },
                },
                "Close"
            );

            // Create the toast
            this.$bvToast.toast(message, {
                id: id,
                toaster: position,
                title: title,
                variant: variant,
                noCloseButton: false,
            });
        },
    }
}
</script>

