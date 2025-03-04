<template>
    <div>
        <div class="row">
            <div class="col-12 mb-3" id="task-search">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
                <i class="fa fa-search fa-lg"></i>
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
                        <form method="post" name="update-task-{{ data.item.id }}" id="update-task-{{ data.item.id }}" :action="fetchRoute('tasks.api.update', {task: data.item.id})" class="col-6">
                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" name="_method" value="patch">
                            <button v-if="!data.item.complete" type="submit" class="btn btn-success"><FontAwesomeIcon title="Complete" icon="fa-solid fa-check" /></button>
                            <button v-else type="submit" class="btn btn-outline-info"><FontAwesomeIcon title="Un-complete" icon="fa-sold fa-undo" /></button>
                        </form>
                    </div>
                    <div v-if="!data.item.complete" class="col-5 me2">
                        <form method="post" name="delete-task-{{ data.item.id }}" id="delete-task-{{ data.item.id }}" :action="fetchRoute('tasks.api.destroy', {task: data.item.id})" class="col-6">
                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-danger"><FontAwesomeIcon title="Delete" icon="fa-solid fa-xmark" /></button>
                        </form>
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
    },
    methods: {
        fetchData(page) {
            if (page) {
                this.current_page = page;
            }

            let indexRoute = route('tasks.api.index', {
                _query: {
                    page: this.current_page,
                    term: this.term,
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
                });
        },
        fetchRoute(routename, param) {
            return route(routename, param);
        }
    },
}
</script>
