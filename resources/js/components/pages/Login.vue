<template>
    <section class="h-100 gradient-form">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-5">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-12">
                                <div class="card-body p-md-5 mx-md-4">
                                    <div class="text-center">
                                    </div>

                                    <form>
                                        <h2 class="text-center">Sign In</h2>

                                        <div class="form-outline mb-4 mt-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input id="email" v-model="email" type="email" class="form-control" required
                                                autofocus autocomplete="off" />
                                        </div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="password">Password</label>
                                            <input v-model="password" id="password" type="password" class="form-control"
                                                required autocomplete="off" />
                                        </div>

                                        <div class="text-center pt-1 pb-1">
                                            <b-button @click="handleSubmit" type="submit" class="btn btn-block
                                                fa-lg
                                                gradient-custom-2
                                                mb-3" variant="primary">
                                                Log in
                                            </b-button>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
  </script>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            error: null,
            isBusy: false
        };
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            if (this.password.length > 0) {
                this.isBusy = true
                var self = this

                axios.get("/sanctum/csrf-cookie").then((response) => {
                    axios
                        .post("/api/login", {
                            email: this.email,
                            password: this.password,
                        })
                        .then((response) => {
                            this.showToast(
                                "Login Success",
                                response.data.message,
                                "success"
                            );
                            setTimeout(() => {
                                location.reload()
                            }, 1400);

                            this.isBusy = false
                        })
                        .catch(function (error) {
                            console.error('err', error);
                            self.showToast("Login Failed", 'Login Error. Please try again', "danger");
                            self.isBusy = false
                        });
                });
            }
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
    },
    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next("events");
        }
        next();
    },
};
</script>
