<template>
    <div>
        <div class="row">
            <div class="col-9 mb-3" id="task-search">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
                <i class="fa fa-search fa-lg"></i>
            </div>
            <div class="col-3">
                <select @change="fetchData(1)" name="user" id="user" v-model="user" class="form-select">
                    <option value="">Please Select...</option>
                    <option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
                </select>
            </div>
        </div>
        <b-table striped hover :items="items" :fields="fields">
            <template #cell(name)="data">
                <div v-if="data.item.complete">
                    <del>{{ data.item.name }}</del>
                </div>
                <div v-else>
                    {{ data.item.name }}
                </div>
            </template>
            <template #cell(user_id)="data">
                <div v-if="data.item.user_id">
                    {{ data.item.user.forename }} {{ data.item.user.surname }}
                </div>
                <div v-else>
                    Not Assigned <!-- need to add functionality here to assign task to user) -->
                </div>
            </template>
            <template #cell(complete)="data">
                <div class="row">
                    <div style="visibility: hidden">
                        complete action
                    </div>
                    <div class="col-5 me-2">
                        <button v-if="!data.item.complete" type="button" class="btn btn-success" @click="changeTaskStatus(data.item.id)"><FontAwesomeIcon title="Complete" icon="fa-solid fa-check" /></button>
                        <button v-else type="button" class="btn btn-outline-info" @click="changeTaskStatus(data.item.id)"><FontAwesomeIcon title="Un-complete" icon="fa-sold fa-undo" /></button>
                    </div>
                    <div v-if="!data.item.complete" class="col-5 me2">
                        <button type="button" class="btn btn-danger" @click="deleteTask(data.item.id)"><FontAwesomeIcon title="Delete" icon="fa-solid fa-xmark" /></button>
                    </div>
                </div>
            </template>
        </b-table>
        <div class="row">
            <div class="col-9">
                <b-pagination @click="fetchData()" :per-page="per_page" v-model="current_page" :total-rows="total_rows"></b-pagination>
            </div>
            <div class="col-3">
                <select @change="fetchData(1)" name="per_page" id="per_page" v-model="per_page" class="form-select">
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import { BTable, BPagination } from "bootstrap-vue-next";

export default {
    name: 'Tasks Table',
    components: { FontAwesomeIcon, BPagination, BTable },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total_rows: 0,
            term: null,
            user: null,
            users: [],
            fields: [
                {
                    key: 'id',
                    label: '#',
                },
                {
                    key: 'name',
                    label: 'Name',
                },
                {
                    key: 'user_id',
                    label: 'Assigned To',
                },
                {
                    key: 'complete',
                    label: 'Actions',
                }
            ],
            items: [],
        }
    },
    mounted() {
        this.fetchData();
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            let indexRoute = route('users.api.index');

            axios.get(indexRoute)
                .then(({data}) => {
                    this.users = data.results;
                })
                .catch(error => {
                    this.$toast.open({
                        message: error.response.data.message,
                        type: 'error',
                        duration: 5000,
                        position: 'top',
                    });
                });
        },
        fetchData(page) {
            if (page) {
                this.current_page = page;
            }

            let indexRoute = route('tasks.api.index', {
                _query: {
                    page: this.current_page,
                    term: this.term,
                    user: this.user,
                    per_page: this.per_page,
                },
            });

            axios.get(indexRoute)
                .then(({data}) => {
                    this.items = data.results.data;
                    this.current_page = data.results.current_page;
                    this.last_page = data.results.last_page;
                    this.total_rows = data.results.total;
                    this.per_page = data.results.per_page;

                    if (this.items.length == 0 && this.last_page > 1) {
                        this.fetchData(this.current_page - 1);
                    }
                })
                .catch(error => {
                    this.$toast.open({
                        message: error.response.data.message,
                        type: 'error',
                        duration: 5000,
                        position: 'top',
                    });
                });
        },
        changeTaskStatus(taskId) {
            axios.post(route('tasks.api.status', {task: taskId}), {
                _token : this.csrf,
                _method: 'patch',
            })
                .then(({data}) => {
                    this.$toast.open({
                        message: data.message,
                        type: 'success',
                        duration: 5000,
                        position: 'top',
                    });

                    this.fetchData();
                })
                .catch(error => {
                    this.$toast.open({
                        message: error.response.data.message,
                        type: 'error',
                        duration: 5000,
                        position: 'top',
                    });
                });
        },
        deleteTask(taskId) {
            axios.post(route('tasks.api.destroy', {task: taskId}), {
                _token : this.csrf,
                _method: 'delete',
            })
                .then(({data}) => {
                    this.$toast.open({
                        message: data.message,
                        type: 'success',
                        duration: 5000,
                        position: 'top',
                    });

                    this.fetchData();
                })
                .catch(error => {
                    this.$toast.open({
                        message: error.response.data.message,
                        type: 'error',
                        duration: 5000,
                        position: 'top',
                    });
                });
        },
    },
}
</script>
