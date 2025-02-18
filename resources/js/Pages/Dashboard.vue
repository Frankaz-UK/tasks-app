<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <template #header>

                Dashboard
            </template>

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
