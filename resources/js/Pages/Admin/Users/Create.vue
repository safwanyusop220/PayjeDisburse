<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// BreadCrumb
import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";

import Content from "@/Components/Content.vue";

// Icons
import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";

import VueMultiselect from 'vue-multiselect'

import { onMounted, watch } from 'vue';

defineProps({
    roles: Array,
    permissions: Array
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
    permissions: []
});

watch(
    form,
    () => {
        console.log(form.data())
    }
)

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Create user" />

        <div class="mt-14">
            <div class="w-full mx-auto">
                <div class="flex justify-between pt-4">
                    <nav class="text-sm font-semibold" aria-label="Breadcrumb">
                        <ol class="list-none inline-flex">
                            <li class="flex items-center">
                                <BreadcrumbInitial :href="route('users.index')"></BreadcrumbInitial>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbActive :href="route('users.index')">Users</BreadcrumbActive>
                            </li>
                            <li class="flex items-center">
                                <BreadcrumbCurrent>Create</BreadcrumbCurrent>
                            </li>
                        </ol>
                    </nav>
                </div>
                <Content>
                    <form @submit.prevent="submit" class="px-2">
                        <div class="row mt-4">
                            <div class="grid grid-cols-2 space-x-4">
                                <div class="">
                                        <InputLabel for="name" value="Name" />

                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.name"
                                            required
                                            autofocus
                                            autocomplete="name"
                                            placeholder="Nama"
                                        />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div class="">
                                    <InputLabel for="email" value="Email" />

                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                        autocomplete="username"
                                        placeholder="Email"
                                    />

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                            </div>
                        </div>


                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />

                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />

                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirm Password" />

                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />

                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="grid grid-cols-2 space-x-4">
                            <div class="mt-4 mb-4">
                                <InputLabel for="roles" value="Roles" />

                                <VueMultiselect
                                v-model="form.roles"
                                :options="roles"
                                :multiple="true"
                                :close-on-select="true"
                                placeholder="Pick some"
                                label="name"
                                track-by="id"
                                />
                            </div>
                        </div>

                        <InputLabel for="Permission" value="Access Details" />

                        <div class="-mt-2 mb-4">
                            <div class="grid grid-cols-3 space-x-4">
                                <template v-for="permission in permissions">
                                    <div class="bg-primary-50 rounded-xl px-2 py-2 mt-4">
                                        <div>
                                            <div class="col">
                                                <div class="row">
                                                    <div class=" items-center" >
                                                    <input
                                                        :key="permission.id"
                                                        type="checkbox"
                                                        :id="permission.id"
                                                        v-model="form.permissions"
                                                        :value="permission"
                                                        class="mr-2 rounded px-2 text-xs"
                                                    />
                                                    <label class="text-xs" :for="permission.id">{{ permission.name.toUpperCase() }}</label>
                                                
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <div class="items-center" >
                                                    <input
                                                        :key="permission.id"
                                                        type="checkbox"
                                                        :id="permission.id"
                                                        v-model="form.permissions"
                                                        :value="permission"
                                                        class="ml-3 mr-2 rounded px-2 text-xs"
                                                    />
                                                    <label class="text-xs" :for="permission.id">{{ permission.name.toUpperCase() }}</label>
                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!--Button Submit-->
                        <div class="flex items-center justify-end space-x-2 mt-8">
                            <Link :href="route('users.index')" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-danger-button hover:bg-danger-button-hover rounded-md">
                                <IcCancel class="text-lg mr-0.5" />
                                Cancel
                            </Link>
                            <button @click="create" :class="{ 'opacity-25 mr-2.5 px-3 py-2 text-md text-white font-semibold bg-primary-500 hover:bg-indigo-700 rounded': form.processing }" :disabled="form.processing" class="flex items-center py-2 px-2 text-xs text-white font-mono bg-success-button hover:bg-success-button-hover rounded-md">
                                <BxCheckDouble class="text-lg mr-0.5" />
                                Submit
                            </button>
                        </div>

                    </form>
                </Content>
            </div>
        </div>
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

