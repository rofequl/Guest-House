<template>
    <div>
        <div class="row">
            <!-- Main Sidebar -->
            <sidebar v-if="user"></sidebar>
            <!-- End Main Sidebar -->
            <router-view :user="user" v-if="!user"></router-view>
            <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
                <headers :user="user" v-if="user"></headers>
                <router-view :user="user" v-if="user"></router-view>
                <footers v-if="user"></footers>
            </main>
        </div>
    </div>
</template>

<script>
    import sidebar from './inc/Sidebar';
    import headers from './inc/Header';
    import footers from './inc/Footer';

    export default {
        data() {
            return {
                user: null,
            }
        },
        methods: {
            loadUser() {
                axios.get('auth/init').then(({data}) => (this.user = data.user));
            },
        },
        created() {
            this.loadUser();
            Fire.$on('LoadUser', () => {
                this.loadUser();
            });
        },
        components: {
            sidebar,
            headers,
            footers
        }
    }
</script>
