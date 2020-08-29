<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <todo-modal v-bind:item="item" v-on:onSaved="refreshData" ref="todoModal"></todo-modal>
                <div class="btn-group float-right">
                    <button class="btn btn-info mr-2" @click="fetchData">Refresh</button>
                    <button class="btn btn-success" @click="createData">New Todo</button>
                </div>

                <h1>Todos</h1>
                <div class="alert alert-danger" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-inline my-2 my-lg-0">
                            <select class="form-control mr-sm-2" v-model="filter" v-on:change="fetchData">
                                <option value="" selected>All</option>
                                <option value="0">Not Complete</option>
                                <option value="1">Completed</option>
                            </select>
                            <input class="form-control mr-sm-2" type="text" placeholder="Search" v-model="search" v-on:keyup="clearSearch">
                            <button class="btn btn-success my-2 my-sm-0" type="submit" @click="fetchData">Search / Filter</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" v-if="list.length">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Full Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="{id, user, title, isCompleted} in list">
                        <td>{{ id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ title }}</td>
                        <td>
                            <button v-if="isCompleted === '1'" class="btn btn-success" @click="changeIsCompleted(id, isCompleted)">Completed</button>
                            <button v-else class="btn btn-primary" @click="changeIsCompleted(id, isCompleted)">Not Complete</button>
                        </td>
                        <td>
                            <button class="btn btn-success" @click="editData(id)">Düzenle</button>
                            <button class="btn btn-danger" @click="deleteData(id)">Sil</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p v-else>Kayıt bulunamadı...</p>
                <pagination :meta="meta" v-on:pageChange="fetchData" />
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from '../../components/includes/Pagination';
import TodoModal from './TodoModal';

export default {
    name: "TodoList",
    components: { Pagination, TodoModal },
    data() {
        return {
            list: [],
            errorMessage: null,
            meta: {},
            item: {},
            search: '',
            filter: ''
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData(page = 1) {
            this.errorMesage = null;
            this.list = [];
            const params = { params: { page } };
            if (this.search) {
                params.params.search = this.search;
            }
            if (this.filter && (this.filter === '0' || this.filter === '1')) {
                params.params.filter = this.filter;
            }
            axios.get('todos', params)
                .then(res => {
                    // console.log(res);
                    this.list = res.data.data.data;
                    this.meta = {
                        current_page: res.data.data.current_page,
                        last_page: res.data.data.last_page
                    }
                })
                .catch(err => {
                    if (err.response !== null)
                        this.errorMesage = err.response.data.message;
                    else
                        this.errorMesage = err.message;
                });
        },
        createData() {
            this.item = {};
            this.$refs.todoModal.errorMessage = '';
            $('#todoModal').modal('show');
        },
        refreshData(item) {
            this.fetchData();
        },
        editData(id) {
            this.item = {};
            this.$refs.todoModal.errorMessage = '';
            axios.get('todos/' + id)
                .then(res => {
                    // console.log(res);
                    this.item = res.data.data;
                    $('#todoModal').modal('show');
                })
                .catch(err => {
                    if (err.response !== null)
                        this.errorMesage = err.response.data.message;
                    else
                        this.errorMesage = err.message;
                });
        },
        deleteData(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete this record?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "No",
                confirmButtonText: "Yes, Delete!",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then(result => {
                if (result.value) {
                    axios.delete('todos/' + id)
                        .then(res => {
                            // console.log(res);
                            this.refreshData();
                            toastr.success(res.data.message);
                        })
                        .catch(err => {
                            if (err.response !== null)
                                this.errorMesage = err.response.data.message;
                            else
                                this.errorMesage = err.message;
                        });
                }
            });
        },
        changeIsCompleted(id, completed) {
            let url = '';
            let msgText = '';
            if (completed === '1') {
                url = `todos/${id}/un_complete`;
                msgText = 'Do you want to change this record to incomplete?'
            } else {
                url = `todos/${id}/complete`;
                msgText = 'Do you want to change this record to complete?'
            }
            Swal.fire({
                title: "Are you sure?",
                text: msgText,
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "No",
                confirmButtonText: "Yes"
            }).then(result => {
                if (result.value) {
                    axios.put(url)
                        .then(res => {
                            // console.log(res);
                            this.refreshData();
                            toastr.success(res.data.message);
                        })
                        .catch(err => {
                            if (err.response !== null)
                                this.errorMesage = err.response.data.message;
                            else
                                this.errorMesage = err.message;
                        });
                }
            });
        },
        clearSearch() {
            if (!this.search) {
                this.fetchData();
            }
        }
    }
}
</script>
