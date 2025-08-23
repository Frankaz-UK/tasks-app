<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit Profile" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div v-if="$page.props.auth.can['profile-update']" class="bg-white p-4 shadow-sm rounded-lg">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
                    </div>
                    <div v-if="$page.props.auth.can['profile-update']" class="bg-white p-4 shadow-sm rounded-lg">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>
                    <div v-if="$page.props.auth.can['profile-delete']" class="bg-white p-4 shadow-sm rounded-lg">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
