<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import TasksTable from "@/Components/TasksTable.vue";
import UsersBarChart from "@/Components/UsersBarChart.vue";
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body text-dark">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center">Welcome back <b class="text-primary">{{ $page.props.auth.fullName }}</b>!</h2>
                            </div>
                            <div class="col-12 pt-4">
                                <h2>Users Tasks Overview</h2>
                                <UsersBarChart></UsersBarChart>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
