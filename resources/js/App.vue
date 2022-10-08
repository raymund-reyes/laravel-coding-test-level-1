<template>


    <div class="h-100">
        <Header @logout="logout" v-if="isLoggedIn" />

        <b-container fluid class="mt-4" v-if="isLoggedIn">
            <router-view class="mt-4" />
        </b-container>

        <Login v-if="!isLoggedIn"/>
    </div>
</template>

<script>
import Header from "./components/layouts/Header";
import Login from "./components/pages/Login.vue";
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
        }
    },
    beforeMount() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true;
        }
    },
    components: {
        Header,
        Login
    },
    methods: {
        logout(e) {
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios
                    .post("/api/logout")
                    .then((response) => {
                        if (response.data) {
                            location.reload()

                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
    }
}
</script>
