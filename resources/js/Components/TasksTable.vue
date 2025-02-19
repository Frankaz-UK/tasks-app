<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { reactive, ref, onMounted } from 'vue';
import { BTable, BPagination } from "bootstrap-vue-next";


// Data
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let current_page = ref(1);
let last_page = ref(1);
let per_page = ref(15);
let total_rows = ref(0);
let term = ref(null);
const fields = reactive([
    {
        key: 'id',
        label: '#',
    },
    {
        key: 'name',
        label: 'Name',
    },
    {
        key: 'complete',
        label: 'Actions',
    }
]);
let items = reactive([]);

// Methods
const fetchData = function(page) {
    if (page) {
        current_page.value = page;
    }

    let indexRoute = route('tasks.api.index', {
        _query: {
            page: current_page.value,
            term: term.value,
            per_page: per_page.value,
        },
    });
    axios.get(indexRoute)
        .then(({data}) => {
            items = data.results.data;
            current_page.value = data.results.current_page;
            last_page.value = data.results.last_page;
            total_rows.value = data.results.total;
            per_page.value = data.results.per_page;
        });
}

const fetchRoute = function(routename, param) {
    return route(routename, param);
}

// Mounted
onMounted(() => {
    fetchData();
})
</script>

<template>
    <div>
        <div class="row">
            <div class="col-12 mb-3">
                <input @input="fetchData(1)" v-model="term" placeholder="Search..." class="form-control" />
            </div>
        </div>
        <BTable striped hover :items="items" :fields="fields">
            <template #cell(name)="data">
                <div v-if="data.item.complete">
                    <del>{{ data.item.name }}</del>
                </div>
                <div v-else>
                    {{ data.item.name }}
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
        </BTable>
        <div class="row">
            <div class="col-9">
                <BPagination @click="fetchData()" :per-page="per_page" v-model="current_page" :total-rows="total_rows"></BPagination>
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
