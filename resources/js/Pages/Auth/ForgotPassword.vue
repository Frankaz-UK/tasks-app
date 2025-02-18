<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <template #header>
            <h2 class="h2">Forgot Password</h2>
        </template>

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Forgot Password</h2>
                                <p class="mt-1 small text-muted">
                                    Forgot your password? No problem. Just let us know your email
                                    address and we will email you a password reset link that will allow
                                    you to choose a new one.
                                </p>
                                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                                    {{ status }}
                                </div>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="email" value="Email" />
                                    <div class="col-sm-10">
                                        <TextInput id="email" type="email" class="form-control" v-model="form.email" autofocus autocomplete="username" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-end gap-4">
                                    <button class="btn btn-primary" :disabled="form.processing">Email Password Reset Link</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
