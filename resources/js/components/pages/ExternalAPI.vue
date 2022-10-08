<template>
    <div>
        <b-row class="justify-content-md-center">
            <h3>Coin Desk API Result</h3>
            <b-col col lg="6">
                <div v-if="isLoading" class="text-center">
                    <b-spinner type="grow" label="Loading External API Result..."></b-spinner>
                    <br />
                    Loading External API Result...
                </div>

                <pre>
                    {{ data }}
                </pre>
            </b-col>
        </b-row>

    </div>
</template>

<script>
export default {
    data() {
        return {
            data: '',
            isLoading: true,
        }
    },

    beforeMount() {
        this.getCoinDesk()
    },
    methods: {
        getCoinDesk() {
            var self = this

            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .get(`/api/v1/external-api/coindesk`, {
                        headers: {
                            'accept': 'application/json'
                        }
                    })
                    .then((response) => {
                        this.isLoading = false
                        this.data = JSON.stringify(response.data, undefined, 2)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                });
        },

    }
}
</script>

