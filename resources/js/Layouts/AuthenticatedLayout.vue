<script setup>
const location = defineModel({ default: window.location.href, type: String });
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
                    <div class="container pt-2 pb-2">
                        <BDropdown :text="$page.props.auth.user.name" variant="primary" class="text-end">
                            <BDropdownItem :active="location === route('dashboard')" :href="route('dashboard')">Dashboard</BDropdownItem>
                            <BDropdownItem :active="location === route('profile.edit')" :href="route('profile.edit')">Edit Profile</BDropdownItem>
                            <BDropdownItem :active="location === route('logout')" :href="route('logout')">Logout</BDropdownItem>
                        </BDropdown>
                    </div>
                </div>
            </div>
        </div>
        <main>
            <slot />
        </main>
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <div>
                &copy Frankaz {{ new Date().getFullYear() }}
            </div>
        </footer>
    </div>
</template>
<script>
console.log(location);
</script>
