<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Reset Password</h2>
                                <p class="mt-1 small text-muted">Need to reset your password? No problem. Just fill in the form below.</p>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <label class="col-sm-2 col-form-label" for="email" value="Email" />
                                    <div class="col-sm-10">
                                        <input id="email" type="email" class="form-control" v-model="form.email" required autofocus autocomplete="username" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <label class="col-sm-2 col-form-label" for="password" value="Password" />
                                    <div class="col-sm-10">
                                        <input id="password" type="password" class="form-control" v-model="form.password" required autocomplete="new-password" />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <label class="col-sm-2 col-form-label" for="password_confirmation" value="Confirm Password"/>
                                    <div class="col-sm-10">
                                        <input id="password_confirmation" type="password" class="form-control" v-model="form.password_confirmation" required autocomplete="new-password" />
                                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                    </div>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-end gap-4">
                                    <button class="btn btn-primary" :disabled="form.processing">Reset Password</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
