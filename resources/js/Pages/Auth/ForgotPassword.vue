<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
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
        <Head title="Forgot Password" />

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
                                <div v-if="status" class="mt-2 font-weight-medium text-success">{{ status }}</div>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <label class="col-sm-2 col-form-label" for="email" value="Email" />
                                    <div class="col-sm-10">
                                        <input id="email" type="email" class="form-control" v-model="form.email" autofocus autocomplete="username" />
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
