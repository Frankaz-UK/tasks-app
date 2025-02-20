<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="name" value="Name" />
                <div class="col-sm-10">
                    <input class="form-control" id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="email" value="Email" />
                <div class="col-sm-10">
                    <input class="form-control" id="email" type="email" v-model="form.email" required autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 small text-muted">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" class="btn btn-secondary">Click here to re-send the verification email.</Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 small font-weight-medium text-success">
                    A new verification link has been sent to your email address.
                </div>
            </div>
            <div class="flex items-center gap-4 mt-2">
                <button class="btn btn-primary" :disabled="form.processing">Save</button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="mt-2 font-weight-medium text-success">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
