<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: false,
    },
    phpVersion: {
        type: String,
        required: false,
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
    <div class="bg-light text-muted">
        <div class="shadow">
            <div class="row header-div pt-2 pb-2">
                <div class="col-6">
                    <header v-if="$slots.header">
                        <div class="container">
                            <h2><slot name="header" /></h2>
                        </div>
                    </header>
                </div>
                <div class="col-6">
                    <div class="container">
                        <nav v-if="canLogin" class="text-end">
                            <template v-if="$page.props.auth.user">
                                <Link :href="route('dashboard')" class="btn btn-primary">Dashboard</Link>
                                <span class="p-2"> </span>
                                <Link :href="route('logout')" method="post" class="btn btn-secondary">Log Out</Link>
                            </template>
                            <template v-else>
                                <Link :href="route('login')" class="btn btn-primary m-1">Log in</Link>
                                <Link v-if="canRegister" :href="route('register')" class="btn btn-secondary m-1">Register</Link>
                            </template>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <main>
            <slot />
        </main>
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <div v-if="laravelVersion !== '' && laravelVersion != null">Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
            <div>&copy Frankaz {{ new Date().getFullYear() }}</div>
        </footer>
    </div>
</template>
