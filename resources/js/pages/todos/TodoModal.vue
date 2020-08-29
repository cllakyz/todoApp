<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="todoModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-text="item.id > 0 ? 'Todo Edit' : 'New Todo'"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errorMessage" v-html="errorMessage"></div>
                    <form @submit.prevent="true">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" v-model="item.title">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" v-model="item.description" cols="3" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="saveItem">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TodoModal",
        props: ['item'],
        data() {
            return {
                errorMessage: ''
            }
        },
        methods: {
            saveItem(){
                if (this.item.id) {
                    axios.patch('todos/' + this.item.id, this.item)
                        .then(res => {
                            // console.log(res);
                            this.$emit('onSaved', this.item);
                            $('#todoModal').modal('hide');
                            toastr.success(res.data.message);
                        })
                        .catch(err => {
                            this.errorMessage = err.response.data.message;
                            if (err.response.data.errors) {
                                this.errorMessage += '<ul>';
                                Object.keys(err.response.data.errors).forEach(key => {
                                    this.errorMessage += '<li>'+err.response.data.errors[key][0]+'</li>';
                                });
                                this.errorMessage += '</ul>';
                            }
                        });
                } else {
                    axios.post('todos', this.item)
                        .then(res => {
                            this.$emit('onSaved', this.item);
                            $('#todoModal').modal('hide');
                            toastr.success(res.data.message);
                        })
                        .catch(err => {
                            this.errorMessage = err.response.data.message;
                            if (err.response.data.errors) {
                                this.errorMessage += '<ul>';
                                Object.keys(err.response.data.errors).forEach(key => {
                                    this.errorMessage += '<li>'+err.response.data.errors[key][0]+'</li>';
                                });
                                this.errorMessage += '</ul>';
                            }
                        });
                }
            }
        }
    }
</script>

<style scoped>
textarea { resize: none; }
</style>
