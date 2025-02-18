<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
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

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <GuestLayout :laravel-version="laravelVersion" :php-version="phpVersion" :can-login="canLogin" :can-register="canRegister">
        <Head title="Welcome" />
        <template v-slot:header>Welcome</template>

        <div class="py-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div v-if="$page.props.auth.user" class="card-body text-dark">
                        You're logged in!
                    </div>
                    <div v-else class="card-body text-dark">
                        Please <Link :href="route('login')">Log in</Link> or <Link v-if="canRegister" :href="route('register')">Register</Link>.
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
