<script setup>
import { Link } from '@inertiajs/vue3';
import { BDropdown, BDropdownItem } from "bootstrap-vue-next";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const location = defineModel({ default: window.location.href, type: String });

defineProps({
    laravelVersion: {
        type: String,
        required: false,
    },
    phpVersion: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <div>
        <header class="shadow">
            <div class="header-div">
                <div class="row pt-2 pb-2">
                    <div class="col-6">
                        <div class="d-inline-flex gap-2">
                            <Link :href="route('home')"><ApplicationLogo class="d-inline-block"/></Link>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="d-inline-flex gap-2">
                            <BDropdown :text="$page.props.auth.full_name" variant="primary">
                                <BDropdownItem :active="location === route('dashboard')" :href="route('dashboard')">Dashboard</BDropdownItem>
                                <BDropdownItem :active="location === route('profile.edit')" :href="route('profile.edit')">Edit Profile</BDropdownItem>
                            </BDropdown>
                            <div><Link :href="route('logout')" method="post" class="btn btn-secondary">Log Out</Link></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <slot />
        </main>
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <div v-if="laravelVersion !== '' && laravelVersion != null">Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
            <div>&copy {{ $page.props.appName }}, Frankaz {{ new Date().getFullYear() }}</div>
        </footer>
    </div>
</template>
