<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

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

const page = usePage();

function routeCheck(url) {
    return url.includes(page.url);
}
</script>

<template>
    <div>
        <header class="shadow">
            <div class="header-div">
                <div class="row pt-2 pb-2">
                    <div class="col-10">
                        <div class="d-inline-flex gap-2">
                            <Link :href="route('home')"><ApplicationLogo class="d-inline-block"/></Link>
                        </div>
                        <div class="d-inline-flex gap-2">
                            &nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="d-inline-flex gap-2">
                            <div><Link :href="route('dashboard.index')" class="btn" :class="routeCheck(route('dashboard.index')) ? 'btn-outline-primary' : 'btn-primary'">Dashboard</Link></div>
                            <div><Link :href="route('tasks.index')" class="btn" :class="routeCheck(route('tasks.index')) ? 'btn-outline-primary' : 'btn-primary'">Tasks</Link></div>
                            <div><Link :href="route('profile.edit')" class="btn" :class="routeCheck(route('profile.edit')) ? 'btn-outline-primary' : 'btn-primary'">Edit Profile</Link></div>
                            <div v-if="$page.props.auth.can['user-list']"><Link :href="route('users.index')" class="btn" :class="routeCheck(route('users.index')) ? 'btn-outline-primary' : 'btn-primary'">Users</Link></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="d-inline-flex gap-2 pt-4">
                            <div ><Link :href="route('logout')" method="post" class="btn btn-secondary">Log Out</Link></div>
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
