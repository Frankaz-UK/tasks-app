<template>
    <div>
        <div class="row">
            <div class="col-9 mb-3 table-search">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
                <i class="fa fa-search fa-lg"></i>
            </div>
            <div class="col-3" v-if="$page.props.auth.can['user-update']">
                <select @change="fetchData(1)" name="roles" id="roles" v-model="role" class="form-select">
                    <option value="">Select Role...</option>
                    <option v-for="role in $page.props.auth.roles" :value="role">{{ role.toString().replace('-', ' ').toLowerCase().replace(/(^| )(\w)/g, s => s.toUpperCase()) }}</option>
                </select>
            </div>
            <div class="col-12">
                <button v-if="$page.props.auth.can['user-create']" class="btn btn-primary" @click="addUser">Add New User</button>
                <BModal v-model="addUserModal" :title="(user_id == null ? 'Add New' : 'Edit') + ' User'" @hide="closeAddUser" size="xl">
                    <div v-if="form.validating" class="text-success">
                        <b>Validating...</b>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="title">Title</label>
                        <div class="col-sm-10">
                            <select name="title" id="title" v-model="form.title" class="form-select" @click="form.validate('title')">
                                <option value="">Select Title...</option>
                                <option v-for="title in titles" :value="title">{{ title }}</option>
                            </select>
                            <div class="row">
                                <div v-if="form.invalid('title')" class="col-sm-12 text-danger">
                                    {{ form.errors.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="forename">Forename</label>
                        <div class="col-sm-10">
                            <input class="form-control" placeholder="Forename" id="forename" name="forename" type="text" v-model="form.forename" required autofocus autocomplete="forename" @focusout="form.validate('forename')" />
                            <div class="row">
                                <div v-if="form.invalid('forename')" class="col-sm-12 text-danger">
                                    {{ form.errors.forename }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="surname">Surname</label>
                        <div class="col-sm-10">
                            <input class="form-control" placeholder="Surname" id="surname" name="surname" type="text" v-model="form.surname" required autofocus autocomplete="surname" @focusout="form.validate('surname')" />
                            <div class="row">
                                <div v-if="form.invalid('surname')" class="col-sm-12 text-danger">
                                    {{ form.errors.surname }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="email">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" placeholder="Email" id="email" name="email" type="email" v-model="form.email" required autocomplete="email" @focusout="form.validate('email')" />
                            <div class="row">
                                <div v-if="form.invalid('email')" class="col-sm-12 text-danger">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="position">Position</label>
                        <div class="col-sm-10">
                            <input class="form-control" placeholder="Position" id="position" name="position" type="text" v-model="form.position" required autofocus autocomplete="position" @focusout="form.validate('position')" />
                            <div class="row">
                                <div v-if="form.invalid('position')" class="col-sm-12 text-danger">
                                    {{ form.errors.position }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="telephone">Telephone</label>
                        <div class="col-sm-10">
                            <input class="form-control" placeholder="Telephone" id="telephone" name="telephone" type="text" v-model="form.telephone" required autofocus autocomplete="telephone" @focusout="form.validate('telephone')" />
                            <div class="row">
                                <div v-if="form.invalid('telephone')" class="col-sm-12 text-danger">
                                    {{ form.errors.telephone }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="gender">Gender</label>
                        <div class="col-sm-10">
                            <select id="gender" name="gender" v-model="form.gender" class="form-select" @click="form.validate('gender')">
                                <option value="">Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="row">
                                <div v-if="form.invalid('gender')" class="col-sm-12 text-danger">
                                    {{ form.errors.gender }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="$page.props.auth.role.includes('super-admin') && form.id !== $page.props.auth.user.id" class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label" for="role">Role</label>
                        <div class="col-sm-10">
                            <select @change="setPermissions()" name="role" id="role" v-model="form.role" class="form-select" @click="form.validate('role')">
                                <option value="">Select Role...</option>
                                <option v-for="role in $page.props.auth.roles" :value="role">{{ role.toString().replace('-', ' ').toLowerCase().replace(/(^| )(\w)/g, s => s.toUpperCase()) }}</option>
                            </select>
                            <div class="row">
                                <div v-if="form.invalid('role')" class="col-sm-12 text-danger">
                                    {{ form.errors.role }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="$page.props.auth.role.includes('super-admin') && form.id !== $page.props.auth.user.id && user_id != null" class="form-group row mt-4">
                        <div class="col-3 mb-2" v-for="(index, can) in $page.props.auth.can">
                            <div class="form-check form-switch">
                                <input
                                    name="selectedPermissions[]"
                                    :value="can"
                                    class="form-check-input"
                                    :id="'permission-' + can"
                                    type="checkbox"
                                    v-model="form.permissions"
                                >
                                <label :for="'permission-' + can" class="form-check-label">{{ can.toString().replace('-', ' ').toLowerCase().replace(/(^| )(\w)/g, s => s.toUpperCase()) }}</label>
                            </div>
                        </div>
                    </div>
                    <template #cancel><button class="btn btn-danger ms-3" @click="closeAddUser">Cancel</button></template>
                    <template #ok><button v-if="$page.props.auth.can['user-update']" class="btn btn-primary ms-3" :disabled="form.processing" @click="saveUser">Submit</button></template>
                </BModal>
            </div>
        </div>
        <b-table striped hover :items="items" :fields="fields">
            <template #cell(roles)="data">
                <div class="row">
                    <div class="col-12 me-2">
                        <span v-for="role in data.item.roles" class="inline-block">
                            {{ role.name.toString().replace('-', ' ').toLowerCase().replace(/(^| )(\w)/g, s => s.toUpperCase()) }}
                        </span>
                    </div>
                </div>
            </template>
            <template #cell(id)="data">
                <div class="row">
                    <div class="col-12 me-2">
                        <span class="inline-block">
                            <button v-if="$page.props.auth.can['user-show']" type="button" class="btn btn-primary" @click="editUser(data.item)"><FontAwesomeIcon title="Edit" icon="fa-solid fa-edit" /> Edit user</button>&nbsp;
                            <button v-if="$page.props.auth.can['user-delete'] && data.item.id !== $page.props.auth.user.id && data.item.roles[0].name !== 'super-admin'" type="button" class="btn btn-danger" @click="deleteUser(data.item.id)"><FontAwesomeIcon title="Delete" icon="fa-solid fa-xmark" /> Delete user</button>
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
import {useForm} from 'laravel-precognition-vue';
import { cloneDeep } from 'lodash';

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
            role: '',
            titles: [],
            user_id: null,
            addUserModal: false,
            fields: [
                {
                    key: 'title',
                    label: 'Title',
                },
                {
                    key: 'forename',
                    label: 'Forename',
                },
                {
                    key: 'surname',
                    label: 'Forename',
                },
                {
                    key: 'email',
                    label: 'Email',
                },
                {
                    key: 'roles',
                    label: 'Role',
                },
                {
                    key: 'id',
                    label: 'Actions',
                },
            ],
            items: [],
            form: useForm('post', '/api/users', {
                id: '',
                title: '',
                forename: '',
                surname: '',
                email: '',
                position: '',
                telephone: '',
                gender: '',
                role: '',
                permissions: [],
            }),
        }
    },
    mounted() {
        this.fetchData();
        this.fetchTitles();
    },
    methods: {
        fetchTitles() {
            axios.get(route('api.titles.list'))
                .then(({data}) => {
                    this.titles = data.results;
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
                    role: this.role,
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
        formatPermissions(permissions) {
            permissions.forEach(function (permission, index, arr) {
                arr[index] = permission.name;
            });

            return permissions;
        },
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
            this.form.forgetError('title');
            this.form.forgetError('forename');
            this.form.forgetError('surname');
            this.form.forgetError('email');
            this.form.forgetError('position');
            this.form.forgetError('telephone');
            this.form.forgetError('gender');
            this.form.forgetError('role');
            this.form.reset();
            this.user_id = null;
        },
        addUser() {
            this.addUserModal = true;
        },
        editUser(user) {
            this.user_id = user.id;
            this.form.id = user.id;
            this.form.title = user.title;
            this.form.forename = user.forename;
            this.form.surname = user.surname;
            this.form.email = user.email;
            this.form.position = user.position;
            this.form.telephone = user.telephone;
            this.form.gender = user.gender;
            this.form.role = user.roles[0].name;
            this.setPermissions(user)
            this.addUser();
        },
        setPermissions(user) {
            let permissions;

            if (user) {
                let userPermissions = cloneDeep(user.permissions);
                let rolePermissions = cloneDeep(user.roles[0].permissions);
                permissions = this.formatPermissions([...new Set([...userPermissions, ...rolePermissions])]);
            } else {
                permissions = this.page.props.auth.rolePermissions[this.form.role] ?? [];
            }

            this.form.permissions = permissions;
        },
        saveUser() {
            let params = (this.user_id == null) ? {} : { user: this.user_id };
            let api_route = (this.user_id == null) ? 'api.users.store' : 'api.users.update';
            let method = (this.user_id == null) ? 'post' : 'patch';

            axios.post(route(api_route, params), {
                id: this.form.id,
                title: this.form.title,
                forename: this.form.forename,
                surname: this.form.surname,
                email: this.form.email,
                position: this.form.position,
                telephone: this.form.telephone,
                gender: this.form.gender,
                role: this.form.role,
                permissions: this.form.permissions,
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
                    this.closeAddUser();
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
