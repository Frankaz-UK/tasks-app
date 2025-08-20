<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
import UsersTable from "@/Components/UsersTable.vue";
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Users" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body text-dark">
                        <h2>Users</h2>
                        <users-table :page="$page"></users-table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
