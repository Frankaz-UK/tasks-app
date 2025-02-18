<script setup>
import { Link } from '@inertiajs/vue3';
import { BDropdown, BDropdownItem } from "bootstrap-vue-next";

const location = defineModel({ default: window.location.href, type: String });
</script>

<template>
    <div class="bg-light text-muted">
        <div class="shadow">
            <div class="row header-div pt-2 pb-2">
                <div class="col-6">
                    <header v-if="$slots.header">
                        <div class="container ">
                            <h2><slot name="header" /></h2>
                        </div>
                    </header>
                </div>
                <div class="col-6 text-end">
                    <div class="d-inline-flex gap-2">
                        <BDropdown :text="$page.props.auth.user.name" variant="primary">
                            <BDropdownItem :active="location === route('dashboard')" :href="route('dashboard')">Dashboard</BDropdownItem>
                            <BDropdownItem :active="location === route('profile.edit')" :href="route('profile.edit')">Edit Profile</BDropdownItem>
                        </BDropdown>
                        <div><Link :href="route('logout')" method="post" class="btn btn-secondary">Log Out</Link></div>
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
<script>
console.log(location);
</script>
