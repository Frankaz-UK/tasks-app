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
            <div class="row header-div">
                <div class="col-6">
                    <header v-if="$slots.header">
                        <div class="container pt-2 pb-2">
                            <h2><slot name="header" /></h2>
                        </div>
                    </header>
                </div>
                <div class="col-6">
                    <nav v-if="canLogin" class="text-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="rounded px-3 py-2 text-black border border-transparent transition hover:text-black-70 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white-80 dark:focus-visible:ring-white">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="btn btn-primary m-1">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="btn btn-secondary m-1">
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </div>
        <main>
            <slot />
        </main>
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <div v-if="laravelVersion !== '' && laravelVersion !== null">
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
        </footer>
    </div>
</template>
