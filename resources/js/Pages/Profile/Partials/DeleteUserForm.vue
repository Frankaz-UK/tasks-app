<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { BModal } from "bootstrap-vue-next";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="mb-4">
        <header>
            <h2 class="font-weight-medium text-dark">Delete Account</h2>
            <p class="mt-1 text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
        </header>
        <button class="btn btn-danger" @click="confirmUserDeletion">Delete Account</button>
        <BModal v-model="confirmingUserDeletion" title="Delete Account" @hide="closeModal" size="xl">
            <div class="p-4">
                <h2 class="h5 font-weight-medium text-dark">Are you sure you want to delete your account?</h2>
                <p class="mt-1 text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                <div class="form-group row mt-4">
                    <InputLabel class="col-sm-2 col-form-label" for="password" value="Password" />
                    <div class="col-sm-10">
                        <TextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="form-control" placeholder="Password" @keyup.enter="deleteUser" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </div>
            </div>
            <template #cancel><button class="btn btn-outline-warning ms-3" @click="closeModal">Cancel</button></template>
            <template #ok><button class="btn btn-outline-danger ms-3" :disabled="form.processing" @click="deleteUser">Delete Account</button></template>
        </BModal>
    </section>
</template>
