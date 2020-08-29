<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error">
                            <p>Invalid credentials.</p>
                        </div>
                        <form autocomplete="off" @submit.prevent="login" method="post">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" v-model="password" required>
                            </div>
                            <button type="submit" class="btn btn-default btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: null,
            password: null,
            has_error: false
        }
    },
    methods: {
        login() {
            // get the redirect object
            const redirect = this.$auth.redirect();
            this.$auth.login({
                params: {
                    email: this.email,
                    password: this.password
                },
                success: function(res) {
                    console.log(res);
                    // handle redirection
                    this.$router.push({ name: 'todoList' });
                },
                error: function(err) {
                    this.has_error = true;
                    console.log(err);
                    console.log(err.response);

                    console.log('error ' + this.context);
                },
                rememberMe: true
            });
        }
    }
}
</script>
