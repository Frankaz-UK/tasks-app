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
                <button class="btn btn-primary" @click="confirmUserDeletion">Add Task</button>
                <BModal v-model="confirmingUserDeletion" title="Delete Account" @hide="closeModal" size="xl">
                    <div class="p-4">
                        <h2 class="h5 font-weight-medium text-dark">Are you sure you want to delete your account?</h2>
                        <p class="mt-1 text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <div class="form-group row mt-4">
                            <label class="col-sm-2 col-form-label" for="password">Password</label>
                            <div class="col-sm-10">
                                <input id="password" v-model="form.password" type="password" class="form-control" placeholder="Password" @keyup.enter="deleteUser" />
                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <template #cancel><button class="btn btn-outline-warning ms-3" @click="closeModal">Cancel</button></template>
                    <template #ok><button class="btn btn-outline-danger ms-3" :disabled="form.processing" @click="deleteUser">Delete Account</button></template>
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
import {BTable, BPagination, BModal} from "bootstrap-vue-next";
import InputError from "@/Components/InputError.vue";
import {nextTick, ref} from "vue";
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
                password: '',
            }),
            confirmingUserDeletion: false,
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
        closeModal() {
            this.confirmingUserDeletion = false;
            /*this.form.clearErrors();
            this.form.reset();*/
        },
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;

            nextTick(() => password.focus());
        },
        deleteUser() {
            /*
            form.delete(route('profile.destroy'), {
                preserveScroll: true,
                onSuccess: () => closeModal(),
                onError: () => passwordInput.value.focus(),
                onFinish: () => form.reset(),
            });
            */
            this.closeModal();
        },
    },
}
</script>
