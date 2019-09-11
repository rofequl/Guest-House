<template>
    <div class="container-fluid">
        <div class="h-100 no-gutters row">
            <div class="auth-form mx-auto mt-3 col-md-5 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <img class="auth-form__logo d-table mx-auto mb-3"
                             src="assets/images/logo.jpg"
                             alt="Shards Dashboards - Login Template"><h5
                        class="auth-form__title text-center mb-4">Access Your Account</h5>
                        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input v-model="form.email" id="email" type="email" class="form-control"
                                       name="email" :class="{ 'is-invalid': form.errors.has('email') }"
                                       autocomplete="email">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model="form.password" id="password" type="password"
                                       class="form-control" name="password"
                                       :class="{ 'is-invalid': form.errors.has('password') }"
                                       autocomplete="current-password">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-1">
                                    <input v-model="form.remember" type="checkbox" name="remember"
                                           class="custom-control-input" id="formsCheckboxChecked">
                                    <label class="custom-control-label" for="formsCheckboxChecked">Remember me</label>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <button :disabled="form.busy" type="submit"
                                        class="d-table mx-auto btn btn-warning text-white btn-pill">
                                    Access Account
                                </button>


                                <router-link class="btn auth-form__meta text-warning" to="register">
                                    Create new account?
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
        props: ['user'],
        name: "Login",
        data() {
            return {
                // Create a new form instance
                form: new Form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },
        methods: {
            login() {
                this.$Progress.start()
                this.form.post('/login')
                    .then(({data}) => {
                        Fire.$emit('LoadUser');
                        this.$Progress.finish()
                        this.$router.push('/');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
        },
        created() {
            Fire.$emit('LoadUser');
        },
    }
</script>

<style scoped>

</style>
