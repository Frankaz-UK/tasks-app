<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body text-dark">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
