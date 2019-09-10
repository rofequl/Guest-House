<template>
    <div class="container-fluid">
        <div class="h-100 no-gutters row">
            <div class="auth-form mx-auto mt-3 col-md-5 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <img class="auth-form__logo d-table mx-auto mb-3"
                             src="assets/images/logo.jpg"
                             alt="Shards Dashboards - Login Template"><h5
                        class="auth-form__title text-center mb-4">Create New Account</h5>
                        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="form.name" id="name" type="text" class="form-control"
                                       name="name" autocomplete="name"
                                       :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="email">E-Mail Address</label>
                                <input v-model="form.email" id="email" type="email" class="form-control"
                                       name="email" autocomplete="email"
                                       :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model="form.password" id="password" type="password"
                                       class="form-control" name="password"
                                       :class="{ 'is-invalid': form.errors.has('password') }"
                                       autocomplete="new-password">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Confirm Password</label>
                                <input v-model="form.password_confirmation" id="password-confirm" type="password"
                                       class="form-control"
                                       name="password_confirmation" autocomplete="new-password"
                                       :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                                <has-error :form="form" field="password_confirmation"></has-error>
                            </div>

                            <div class="form-group row mb-0">
                                <button :disabled="form.busy" type="submit"
                                        class="d-table mx-auto btn btn-warning text-white btn-pill">
                                    Create Account
                                </button>
                                <router-link class="btn auth-form__meta text-warning" to="login">
                                    Sign In?
                                </router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Register",
        data() {
            return {
                // Create a new form instance
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                })
            }
        },
        methods: {
            login() {
                // Submit the form via a POST request
                this.form.post('/register')
                    .then(({data}) => {
                        Fire.$emit('LoadUser');
                        this.$router.push('/');
                    })
            }
        }
    }
</script>

<style scoped>

</style>
