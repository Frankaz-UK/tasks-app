<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>

<template>
    <GuestLayout :laravel-version="laravelVersion" :php-version="phpVersion" :can-login="canLogin" :can-register="canRegister">
        <template #header>
            <h2 class="h2">Login</h2>
        </template>

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Login</h2>
                                <p class="mt-1 small text-muted">Please fill out the details below to login.</p>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="email" value="Email" />
                                    <div class="col-sm-10">
                                        <TextInput id="email" type="email" class="form-control" v-model="form.email" required autofocus autocomplete="username" />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="password" value="Password" />
                                    <div class="col-sm-10">
                                        <TextInput id="password" type="password" class="form-control" v-model="form.password" required autocomplete="current-password" />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-center gap-4">
                                    <label class="flex items-center">
                                        <Checkbox name="remember" v-model:checked="form.remember" />
                                        <span class="p-1"> </span>
                                        <label class="form-label" for="remember">Remember Me</label>
                                    </label>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-end gap-4">
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Forgot your password?</Link>
                                    <button class="btn btn-primary" :disabled="form.processing">Log in</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="status" class="mb-4 small fw-medium text-success">
            {{ status }}
        </div>
    </GuestLayout>
</template>
