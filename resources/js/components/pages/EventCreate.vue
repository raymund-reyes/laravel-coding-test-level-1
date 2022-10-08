<template>
    <div>
        <b-row class="justify-content-md-center mt-5">
            <b-col col lg="6">

                <div>
                    <b-card-group deck>
                        <b-card title="Create New Event">
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
                            <b-button size="sm" variant="primary" @click="submit()">
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
            endAt: ''
        }
    },

    methods: {

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
                .post(`/api/v1/events/`,
                    {
                        name: this.eventName,
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
                            "Event Created",
                            response.data.message,
                            "success"
                        );
                        this.eventName = ''
                        this.eventSlug = ''
                        this.startAt = ''
                        this.endAt = ''

                        setTimeout(() => {
                            self.$router.push('/events')
                        }, 3000)
                    } else {
                        this.error = response.data;
                        this.showToast(
                            "Event Create Failed",
                            this.error,
                            "danger"
                        );
                    }

                })
                .catch(function (error) {
                    console.error(error);
                    self.showToast(
                        "Event Create Failed",
                        "Event Create Error. Please try again",
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
            console.log("TOAST")
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

