<template>


    <div class="h-100">
        <Header />

        <b-container fluid class="mt-4">
            <router-view class="mt-4" />
        </b-container>
    </div>
</template>

<script>
import Header from "./components/layouts/header";
export default {
    name: "App",
    components: {
        Header
    }
}
</script>
