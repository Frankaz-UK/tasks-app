<template>
    <div>
        <div class="row">
            <div class="col-9 mb-3" id="task-search">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
                <i class="fa fa-search fa-lg"></i>
            </div>
            <div class="col-3">
                <select @change="fetchData(1)" name="user" id="user" v-model="user" class="form-select">
                    <option value="">Select User...</option>
                    <option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" @click="addTask">Add New Task</button>
                <BModal v-model="addTaskModal" title="Add New Task" @hide="closeAddTask" size="xl">
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="name">Task Name:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="Task name" ref="task_name" id="name" name="name" v-model="form.name" />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="name">Task Name:</label>
                        <div class="col-sm-10">
                            <textarea rows="10" class="form-control" type="text" placeholder="Task description" id="description" name="description" v-model="form.description"></textarea>
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="user_id">User:</label>
                        <div class="col-sm-10">
                            <select name="user_id" id="user_id" v-model="form.user_id" class="form-select">
                                <option value="" selected>Select User...</option>
                                <option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
                            </select>
                            <InputError :message="form.errors.user_id" class="mt-2" />
                        </div>
                    </div>
                    <template #cancel><button class="btn btn-danger ms-3" @click="closeAddTask">Cancel</button></template>
                    <template #ok><button class="btn btn-primary ms-3" :disabled="form.processing" @click="saveTask">Submit</button></template>
                </BModal>
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
                        <button v-if="!data.item.complete" type="button" class="btn btn-success" @click="changeTaskStatus(data.item.id)"><FontAwesomeIcon title="Complete" icon="fa-solid fa-check" /> Mark Complete</button>
                        <button v-else type="button" class="btn btn-outline-info" @click="changeTaskStatus(data.item.id)"><FontAwesomeIcon title="Un-complete" icon="fa-sold fa-undo" /> Unmark</button>
                    </div>
                    <div v-if="!data.item.complete" class="col-5 me2">
                        <button type="button" class="btn btn-danger" @click="deleteTask(data.item.id)"><FontAwesomeIcon title="Delete" icon="fa-solid fa-xmark" /> Delete Task</button>
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
import {BTable, BPagination, BModal} from "bootstrap-vue-next";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";

export default {
    name: 'Tasks Table',
    components: {InputError, BModal, FontAwesomeIcon, BPagination, BTable },
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total_rows: 0,
            term: null,
            user: '',
            users: [],
            form: useForm({
                name: '',
                description: '',
                user_id: '',
            }),
            addTaskModal: false,
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
                    key: 'description',
                    label: 'Description',
                    thStyle: { width: "20%" },
                    tdStyle: { width: "20%" },
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
                        message: error.response ? error.response.data.message : error,
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
        closeAddTask() {
            this.addTaskModal = false;
            this.form.clearErrors();
            this.form.reset();
        },
        addTask() {
            this.addTaskModal = true;
        },
        saveTask() {
            axios.post(route('tasks.api.store', {
                // may be used if same method for task update, however might need to prepare above then no problems with route etc.
            }), {
                name: this.form.name,
                description: this.form.description,
                user_id: this.form.user_id,
                _token : this.csrf,
                _method: 'post',
            })
                .then(({data}) => {
                    this.$toast.open({
                        message: data.message,
                        type: 'success',
                        duration: 5000,
                        position: 'top',
                    });

                    this.fetchData();
                    this.closeAddTask();
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
        updateTask() { // not implemented yet, requires new modal? or just change route and method (can use another model to store the task id)?
            axios.post(route('tasks.api.update', {
                name: this.form.name,
                description: this.form.description,
                user_id: this.user
            }), {
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
                    this.closeAddTask();
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
