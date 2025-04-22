<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import TasksTable from "@/Components/TasksTable.vue";
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tasks" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body text-dark">
                        Tasks
                        <tasks-table></tasks-table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
