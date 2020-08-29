<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Invalid Credentials! Please try again.</p>
                            <p v-else>Error, unable register user</p>
                        </div>
                        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="POST">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control" placeholder="John Doe" v-model="name">
                                <span class="help-block" v-if="has_error && errors.name">{{ errors.name }}</span>
                            </div>
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.email }">
                                <label for="email">E-Mail</label>
                                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email">
                                <span class="help-block" v-if="has_error && errors.email">{{ errors.email }}</span>
                            </div>
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" v-model="password">
                                <span class="help-block" v-if="has_error && errors.password">{{ errors.password }}</span>
                            </div>
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                                <label for="password_confirmation">Password Confirmation</label>
                                <input type="password" id="password_confirmation" class="form-control" v-model="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
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
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            has_error: false,
            error: '',
            errors: {},
            success: false
        }
    },
    methods: {
        register() {
            this.$auth.register({
                data: {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                },
                success: () => {
                    this.success = true;
                    this.$router.push({name: 'todoList'});
                },
                error: (res) => {
                    // console.log(res.response.data.errors);
                    this.success = false;
                    this.has_error = true;
                    this.error = res.response.data.error;
                    this.errors = res.response.data.errors || {};
                }
            });
        }
    }
}
</script>
