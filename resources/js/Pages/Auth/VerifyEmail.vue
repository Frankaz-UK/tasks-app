<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verify Email" />

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="bg-white p-4 shadow-sm rounded-lg">
                        <section>
                            <header>
                                <h2 class="font-weight-medium text-dark">Verify Email</h2>
                                <div class="mt-1 small text-muted">
                                    Thanks for signing up! Before getting started, could you verify your
                                    email address by clicking on the link we just emailed to you? If you
                                    didn't receive the email, we will gladly send you another.
                                </div>
                                <div class="mt-2 font-weight-medium text-success" v-if="verificationLinkSent">
                                    A new verification link has been sent to the email address you
                                    provided during registration.
                                </div>
                            </header>
                            <form @submit.prevent="submit">
                                <div class="mt-4 d-flex align-items-center justify-content-center gap-4">
                                    <button class="btn btn-primary" :disabled="form.processing">Resend Verification Email</button>
                                    <Link :href="route('logout')" method="post" class="btn btn-secondary">Log Out</Link>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
