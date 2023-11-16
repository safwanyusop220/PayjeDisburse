<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import BreadcrumbInitial from "@/Components/BreadcrumbInitial.vue";
import BreadcrumbActive from "@/Components/BreadcrumbActive.vue";
import BreadcrumbCurrent from "@/Components/BreadcrumbCurrent.vue";
import { Datepicker, Input, initTE, Select } from "tw-elements";

import Content from "@/Components/Content.vue";

import { IcCancel } from "@kalimahapps/vue-icons";
import { QuSendCancelled } from "@kalimahapps/vue-icons";
import { BxCheckDouble } from "@kalimahapps/vue-icons";

import VueMultiselect from 'vue-multiselect'

import { ref, watch } from 'vue';
import { onMounted } from 'vue';

onMounted(() => {
  initTE({ Datepicker, Input, Select });
});

defineProps({
    roles: Array,
    permissions: Array,
    groups: Array
})


const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: '',
    selectedRole: '',
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
        onFinish: () => form.reset('password'),
    });
};

const defineAccessChecked = ref(false);
const defaultPasswordChecked = ref(false);

const getDefaultPassword = () => {
  return import.meta.env.VITE_DEFAULT_PASSWORD; 
};

const generatePassword = () => {
  const newPassword = getDefaultPassword();
  form.password = newPassword;
};

watch(defaultPasswordChecked, (newVal) => {
  if (newVal) {
    generatePassword();
  } else {
    form.password = '';
  }
});

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
                    <div class="flex justify-between">
                        <span class="font-medium text-primary-text text-lg mt-2 ml-2">Create User</span>
                    </div>

                    <form @submit.prevent="submit" class="px-2">
                        <!--Name & Email-->
                        <div class="row mt-4">
                            <div class="grid grid-cols-2 space-x-4">
                                <div class="mb-6">
                                    <div class="bg-white">
                                        <div class="relative bg-inherit">
                                            <input type="text" id="name" v-model="form.name" required 
                                                class="w-full text-xs shadow-sm border border-gray-300 peer bg-transparent rounded-md text-primary-500 placeholder-transparent ring-0 px-2 ring-gray-300 focus:ring-gray-300 focus:outline-none focus:border-gray-300" placeholder="."
                                            />
                                            <label class="absolute cursor-text left-0 -top-2 text-xs text-primary-500 bg-inherit mx-1 px-1 peer-placeholder-shown:text-xs peer-placeholder-shown:text-gray-300 peer-placeholder-shown:top-2 peer-focus:-top-2 peer-focus:text-primary-500 peer-focus:text-xs peer-focus:text-extrabold transition-all">
                                                Name
                                            </label>
                                        </div>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="mb-6">
                                    <div class="bg-white">
                                        <div class="relative bg-inherit">
                                            <input type="email" id="email" v-model="form.email" required 
                                                class="w-full text-xs shadow-sm border border-gray-300 peer bg-transparent rounded-md text-primary-500 placeholder-transparent ring-0 px-2 ring-gray-300 focus:ring-gray-300 focus:outline-none focus:border-gray-300" placeholder="."
                                            />
                                            <label class="absolute cursor-text left-0 -top-2 text-xs text-primary-500 bg-inherit mx-1 px-1 peer-placeholder-shown:text-xs peer-placeholder-shown:text-gray-300 peer-placeholder-shown:top-2 peer-focus:-top-2 peer-focus:text-primary-500 peer-focus:text-xs peer-focus:text-extrabold transition-all">
                                                Email
                                            </label>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="grid grid-cols-2 space-x-4">
                                
                                <div class="">
                                    <!--Password & Confirm Password-->
                                    <div class="row">
                                        <div class="grid grid-cols-2 space-x-4">
                                            <div class="">
                                                <div class="bg-white">
                                                    <div class="relative bg-inherit">
                                                        <input type="password" id="password" v-model="form.password" required 
                                                            class="w-full text-xs shadow-sm border border-gray-300 peer bg-transparent rounded-md text-primary-500 placeholder-transparent ring-0 px-2 ring-gray-300 focus:ring-gray-300 focus:outline-none focus:border-gray-300" placeholder="."
                                                        />
                                                        <label class="absolute cursor-text left-0 -top-2 text-xs text-primary-500 bg-inherit mx-1 px-1 peer-placeholder-shown:text-xs peer-placeholder-shown:text-gray-300 peer-placeholder-shown:top-2 peer-focus:-top-2 peer-focus:text-primary-500 peer-focus:text-xs peer-focus:text-extrabold transition-all">
                                                            Password
                                                        </label>
                                                    </div>
                                                </div>

                                                <InputError class="mt-2" :message="form.errors.password" />
                                            </div>

                                            <div class="my-auto">
                                                <div class="bg-white border border-gray-300 rounded-md py-1 px-2">
                                                    <div class="">
                                                        <input
                                                            type="checkbox" v-model="defaultPasswordChecked"
                                                            class="mr-2 rounded border-primary-300 focus:ring-white text-xs"/>
                                                        <label class="text-xxs font-semibold uppercase text-primary-700">Default Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Roles-->
                                <div class="">
                                    <div class="row">
                                        <div class="grid grid-cols-2 space-x-4">
                                            <div class="">  
                                                <select
                                                    data-te-select-init data-te-select-size="sm"
                                                    class="w-full text-primary-700 border-gray-300 bg-white rounded-md"
                                                    v-model="form.selectedRole"
                                                    id="allocation"
                                                    >
                                                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                                </select>
                                                <label data-te-select-label-ref>Role</label>

                                                <InputError class="mt-2" :message="form.errors.roles" />
                                            </div>
                                            <div class="">
                                                <div class="bg-white border border-gray-300 rounded-lg  px-2 py-1">
                                                    <input
                                                        type="checkbox" v-model="defineAccessChecked"
                                                        class="mr-2 rounded border-primary-300 focus:ring-white text-xs"/>
                                                    <label class="text-xxs font-bold uppercase text-primary-700">Define Access</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <InputLabel for="Permission" value="Access Details" />

                        <!--All Permission-->
                        <div class="bg-primary-100 rounded-xl px-2 pt-1 pb-2 mt-1 mr-2">
                            <div class="">
                                <input
                                    type="checkbox" :disabled="!defineAccessChecked"
                                    class="mr-1 rounded border-primary-300 focus:ring-primary-100 px-2 text-xs"
                                    :class="{ 'border-primary-300': defineAccessChecked, 'border-primary-100': !defineAccessChecked }"
                                />
                                <label class="text-xxs font-bold uppercase"
                                :class="{ 'text-primary-700': defineAccessChecked, 'text-primary-300': !defineAccessChecked }">
                                    All Permission
                                </label>
                            </div>
                        </div>

                        <!--Access Details-->
                        <div class="">
                            <div class="grid grid-cols-3">
                                <template v-for="group in groups">
                                    <div class="bg-primary-100 rounded-xl px-2 py-2 mt-2 mr-2">
                                        <div class="">
                                            <input
                                                :key="group.id"
                                                type="checkbox"
                                                :id="group.id"
                                                :value="group.id" :disabled="!defineAccessChecked"
                                                class="mr-1 rounded  focus:ring-primary-100 px-2 text-xs"
                                                :class="{ 'border-primary-300': defineAccessChecked, 'border-primary-100': !defineAccessChecked }"
                                            />
                                            <label class="text-xxs font-bold" 
                                            :class="{ 'text-primary-700': defineAccessChecked, 'text-primary-300': !defineAccessChecked }"
                                            :for="group.id">
                                                {{ group.name.toUpperCase() }}
                                            </label>
                                        </div>
                                        
                                        <template v-for="permission in group.permissions">                                            
                                            <div class="ml-5 -mt-1.5">
                                                <input
                                                    :key="permission.id"
                                                    type="checkbox"
                                                    :id="permission.name"
                                                    v-model="form.permissions"
                                                    :value="permission" :disabled="!defineAccessChecked"
                                                    class="mr-1 rounded focus:ring-primary-100 px-2 text-xs"
                                                    :class="{ 'border-primary-300': defineAccessChecked, 'border-primary-100': !defineAccessChecked }"
                                                />
                                                <label class="text-xxs font-medium" 
                                                :class="{ 'text-primary-700': defineAccessChecked, 'text-primary-300': !defineAccessChecked }"
                                                :for="permission.id"
                                                >{{ permission.name }}</label>
                                            </div>
                                        </template>
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

