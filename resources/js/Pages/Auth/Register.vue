<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Register</h2>
                                <p class="mt-1 small text-muted">Please fill out the details below to register an account.</p>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="name" value="Name" />
                                    <div class="col-sm-10">
                                        <TextInput id="name" type="text" class="form-control" v-model="form.name" autofocus autocomplete="name" />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="email" value="Email" />
                                    <div class="col-sm-10">
                                        <TextInput id="email" type="email" class="form-control" v-model="form.email" autocomplete="username" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="password" value="Password" />
                                    <div class="col-sm-10">
                                        <TextInput id="password" type="password" class="form-control" v-model="form.password" autocomplete="new-password" />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="password_confirmation" value="Confirm Password" />
                                    <div class="col-sm-10">
                                        <TextInput id="password_confirmation" type="password" class="form-control" v-model="form.password_confirmation" autocomplete="new-password" />
                                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                    </div>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-end gap-4">
                                    <Link :href="route('login')" class="btn btn-secondary">Already registered?</Link>
                                    <button class="btn btn-primary" :disabled="form.processing">Register</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
