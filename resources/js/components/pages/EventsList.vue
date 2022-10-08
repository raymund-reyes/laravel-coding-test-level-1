<template>
    <div>
        <b-row>
            <b-col lg="9">
                <h3>
                    All Events
                </h3>
            </b-col>
            <b-col class="flex justify-content-end text-right" lg="3">
                <a @click="$router.push('/events/create')" class="btn btn-sm btn-primary">
                    <b-icon aria-hidden="true" icon="folder-plus" />
                    Create an Event
                </a>
            </b-col>
        </b-row>
        <b-row class="justify-content-md-center mt-3">
            <b-col col lg="8">
                <b-row class="flex justify-content-end mb-2">
                    <b-col lg="6">
                        <b-form-group label-for="filter-input">
                            <b-input-group size="sm">
                                <b-form-input id="filter-input" v-model="filter" type="search"
                                    placeholder="Type to Search"></b-form-input>

                                <b-input-group-append>
                                    <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-table :items="events" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" stacked="md" show-empty small :busy="isLoading">

                    <template #table-busy>
                        <div class="text-center theme-font-color-1 my-1">
                            <b-spinner class="align-middle" small></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <template #cell(name)="event">
                        <strong role="button" @click="$router.push(`/events/${event.item.id}`)">{{ event.item.name}}</strong>
                    </template>
                    <template #cell(actions)="event">
                        <button @click="$router.push(`/events/${event.item.id}/edit`)" class="btn btn-sm btn-primary">
                            <b-icon icon="pencil" /> Edit
                        </button>
                        <button @click="deleteEvent(event.item)" class="btn btn-sm btn-danger">
                            <b-icon icon="trash" /> Delete
                        </button>
                    </template>
                </b-table>
                <b-row>
                    <b-col lg="4">

                        <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill"
                            size="sm" class="my-0"></b-pagination>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>

        <DeleteEvent :eventInfo='eventInfo' @showToast="showToast" @reloadPage="getAllEvents" />
    </div>
</template>

<script>
import DeleteEvent from '../modals/DeleteEvent';
export default {
    data() {
        return {
            isLoading: true,
            events: [],
            eventInfo: {},
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            filter: '',
            fields: [
                { key: 'id', label: 'ID', sortable: true, sortDirection: 'desc' },
                { key: 'name', label: 'Event Name', sortable: true, class: 'text-center' },
                { key: 'startAt', label: 'Event Start', sortable: true, class: 'text-center' },
                { key: 'endAt', label: 'Event End', sortable: true, class: 'text-center' },
                { key: 'actions', label: 'Actions' }
            ],
        }
    },
    components: {
        DeleteEvent,
    },
    beforeMount() {
        this.getAllEvents()
    },
    methods: {
        getAllEvents() {
            var self = this

            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .get("/api/v1/events", {
                        headers: {
                            'accept': 'application/json'
                        }
                    })
                    .then((response) => {
                        this.isLoading = false
                        this.events = response.data
                        this.totalRows = this.events.length
                    })
                    .catch(function (error) {
                        console.error(error);
                        self.isLoading = false
                        self.loading = false
                    });
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
        deleteEvent(data) {
            this.eventInfo = data
            this.$bvModal.show('delete-event')
        },
    }
}
</script>

