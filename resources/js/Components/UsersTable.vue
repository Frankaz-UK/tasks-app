<template>
    <div>
        <div class="row">
            <div class="col-9 mb-3 table-search">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
                <i class="fa fa-search fa-lg"></i>
            </div>
            <div class="col-3" v-if="$page.props.auth.can['user-update']">
                <select @change="fetchData(1)" name="user" id="user" v-model="user" class="form-select">
                    <option value="">Select User...</option>
                    <option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
                </select>
            </div>
            <!--div class="col-12"> // not built yet
                <button class="btn btn-primary" @click="adduser">Add New user</button>
                <BModal v-model="addUserModal" :title="(user_id == null ? 'Add New' : 'Edit') + ' user'" @hide="closeAdduser" size="xl">
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="name">user Name:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" placeholder="user name" ref="user_name" id="name" name="name" v-model="form.name" />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="name">user Name:</label>
                        <div class="col-sm-10">
                            <textarea rows="10" class="form-control" type="text" placeholder="user description" id="description" name="description" v-model="form.description"></textarea>
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>
                    <div v-if="$page.props.auth.can['user-update']" class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="user_id">User:</label>
                        <div class="col-sm-10">
                            <select name="user_id" id="user_id" v-model="form.user_id" class="form-select">
                                <option value="" selected>Select User...</option>
                                <option v-for="user in users" :value="user.id">{{ user.fullname }}</option>
                            </select>
                            <InputError :message="form.errors.user_id" class="mt-2" />
                        </div>
                    </div>
                    <template #cancel><button class="btn btn-danger ms-3" @click="closeAdduser">Cancel</button></template>
                    <template #ok><button v-if="$page.props.auth.can['user-update']" class="btn btn-primary ms-3" :disabled="form.processing" @click="saveuser">Submit</button></template>
                </BModal>
            </div-->
        </div>
        <b-table striped hover :items="items" :fields="fields">
            <template #cell(complete)="data">
                <div class="row">
                    <div class="col-12 me-2">
                        <span v-if="$page.props.auth.can['user-complete']" class="inline-block me-2">
                            <button v-if="!data.item.complete" type="button" class="btn btn-success" @click="changeuserStatus(data.item.id)"><FontAwesomeIcon title="Complete" icon="fa-solid fa-check" /> Mark Complete</button>
                            <button v-else type="button" class="btn btn-outline-info" @click="changeuserStatus(data.item.id)"><FontAwesomeIcon title="Un-complete" icon="fa-sold fa-undo" /> Unmark</button>
                        </span>
                        <span v-if="!data.item.complete" class="inline-block">
                            <button v-if="$page.props.auth.can['user-show']" type="button" class="btn btn-primary" @click="edituser(data.item)"><FontAwesomeIcon title="Edit" icon="fa-solid fa-edit" /> Edit user</button>&nbsp;
                            <button v-if="$page.props.auth.can['user-delete']" type="button" class="btn btn-danger" @click="deleteuser(data.item.id)"><FontAwesomeIcon title="Delete" icon="fa-solid fa-xmark" /> Delete user</button>
                        </span>
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
    name: 'Users Table',
    components: {InputError, BModal, FontAwesomeIcon, BPagination, BTable },
    props: {
        page: Object,
    },
    data() {
        return {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total_rows: 0,
            term: null,
            user: this.page.props.auth.roles.includes('user') ? this.page.props.auth.user.id : '',
            users: [],
            form: useForm({
                forename: '',
                surname: '',
                email: '',
                position: '',
                telephone: '',
                gender: '',
            }),
            user_id: null,
            addUserModal: false,
            fields: [
                {
                    key: 'title',
                    label: 'Title',
                    thStyle: { width: "15%" },
                    tdStyle: { width: "15%" },
                },
                {
                    key: 'forename',
                    label: 'Forename',
                    thStyle: { width: "20%" },
                    tdStyle: { width: "20%" },
                },
                {
                    key: 'surname',
                    label: 'Forename',
                    thStyle: { width: "20%" },
                    tdStyle: { width: "20%" },
                },
                {
                    key: 'email',
                    label: 'Email',
                    thStyle: { width: "20%" },
                    tdStyle: { width: "20%" },
                },
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
            axios.get(route('api.users.list'))
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

            let indexRoute = route('api.users.index', {
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
        /* following not yet implemented
        deleteUser(userId) {
            axios.post(route('api.users.destroy', {user: userId}), {
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
        closeAddUser() {
            this.addUserModal = false;
            this.form.clearErrors();
            this.form.reset();
            this.user_id = null;
        },
        addUser() {
            this.addUserModal = true;
        },
        editUser(user) {
            this.user_id = user.id;
            this.form.name = user.name;
            this.form.description = user.description;
            this.form.user_id = user.user_id;
            this.addUser();
        },
        saveUser() {
            let api_route = (this.user_id == null) ? 'api.users.store' : 'api.users.update'; // change to be based on role instead
            let params = (this.user_id == null) ? {} : { user: this.user_id };
            let method = (this.user_id == null) ? 'post' : 'patch';

            axios.post(route(api_route, params), {
                name: this.form.name,
                description: this.form.description,
                user_id: this.form.user_id,
                _method: method,
            })
                .then(({data}) => {
                    this.$toast.open({
                        message: data.message,
                        type: 'success',
                        duration: 5000,
                        position: 'top',
                    });

                    this.fetchData();
                    this.closeAdduser();
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
        */
    },
}
</script>
