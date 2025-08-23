<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import {useToast} from "vue-toast-notification";

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
    title: user.title,
    forename: user.forename,
    surname: user.surname,
    email: user.email,
    position: user.position,
    telephone: user.telephone,
    gender: user.gender,
});

const titles = ref([]);

onMounted(async () => {
    axios.get(route('api.titles.list'))
        .then(({data}) => {
            titles.value = data.results;
        })
        .catch(error => {
            useToast().open({
                message: error.response ? error.response.data.message : error,
                type: 'error',
                duration: 5000,
                position: 'top',
            });
        });
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
                <label class="col-sm-2 col-form-label" for="title">Title</label>
                <div class="col-sm-10">
                    <select name="title" id="title" v-model="form.title" class="form-select" autofocus>
                        <option value="">Select Title...</option>
                        <option v-for="(title, index) in titles" :value="title" :key="index">{{ title }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="forename">Forename</label>
                <div class="col-sm-10">
                    <input class="form-control" id="forename" name="forename" type="text" v-model="form.forename" required autofocus autocomplete="forename" />
                    <InputError class="mt-2" :message="form.errors.forename" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="surname">Surname</label>
                <div class="col-sm-10">
                    <input class="form-control" id="surname" name="surname" type="text" v-model="form.surname" required autofocus autocomplete="surname" />
                    <InputError class="mt-2" :message="form.errors.surname" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email</label>
                <div class="col-sm-10">
                    <input class="form-control" id="email" name="email" type="email" v-model="form.email" required autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="position">Position</label>
                <div class="col-sm-10">
                    <input class="form-control" id="position" name="position" type="text" v-model="form.position" required autofocus autocomplete="position" />
                    <InputError class="mt-2" :message="form.errors.position" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="telephone">Telephone</label>
                <div class="col-sm-10">
                    <input class="form-control" id="telephone" name="telephone" type="text" v-model="form.telephone" required autofocus autocomplete="telephone" />
                    <InputError class="mt-2" :message="form.errors.telephone" />
                </div>
            </div>
            <div class="mt-2 form-group row">
                <label class="col-sm-2 col-form-label" for="gender">Gender</label>
                <div class="col-sm-10">
                    <select id="gender" name="gender" v-model="form.gender" class="form-select">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.gender" />
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
