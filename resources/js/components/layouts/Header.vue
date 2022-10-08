<template>
    <div>
        <b-navbar toggleable="lg" type="dark" class="bg-primary">
            <b-navbar-brand>Laravel Exam</b-navbar-brand>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item @click="$router.push('/events')">Events</b-nav-item>
                    <b-nav-item @click="$router.push('external-api')">External API</b-nav-item>
                </b-navbar-nav>
                <b-navbar-nav class="ml-auto">

                    <b-nav-item-dropdown right>
                        <template #button-content>

                            <em>Welcome,</em> User
                        </template>
                        <b-dropdown-item @click="logout">Sign Out</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
export default {
    name: "Header",
    methods: {
        logout() {
            this.$emit("logout", true);
        },

        goto(link) {
            if (link != this.$route.name) {
                this.$router.push(link)
            }
        }
    },
};
</script>

