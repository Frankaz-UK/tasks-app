<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />
        <template #header>Confirm Password</template>

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Confirm Password</h2>
                                <p class="mt-1 small text-muted">This is a secure area of the application. Please confirm your password before continuing.</p>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-2 form-group row">
                                    <InputLabel class="col-sm-2 col-form-label" for="password" value="Password" />
                                    <div class="col-sm-10">
                                        <TextInput id="password" type="password" class="form-control" v-model="form.password" required autocomplete="current-password" autofocus />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>
                                </div>
                                <div class="mt-4 d-flex align-items-center justify-content-end gap-4">
                                    <button class="btn btn-primary" :disabled="form.processing">Confirm</button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
