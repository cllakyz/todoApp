<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <router-link class="navbar-brand" to="/">Laravel-Vue Todo App</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li v-if="!$auth.check()" v-for="(route, key) in routes.unlogged" v-bind:key="route.path">
                        <router-link class="nav-link" :to="{ name : route.path }" :key="key">
                            {{route.name}}
                        </router-link>
                    </li>
                    <li v-if="$auth.check()">
                        <a href="#" class="nav-link" @click.prevent="userLogout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "Navbar",
        data() {
            return {
                routes: {
                    // UNLOGGED
                    unlogged: [
                        {
                            name: 'Register',
                            path: 'register'
                        },
                        {
                            name: 'Login',
                            path: 'login'
                        }
                    ]
                }
            }
        },
        mounted() {
            //
        },
        methods: {
            userLogout() {
                this.$auth.logout();
                // this.$router.push({name: 'login'});
            }
        }
    }
</script>
