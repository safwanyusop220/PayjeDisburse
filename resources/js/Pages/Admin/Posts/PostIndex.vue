<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from "vue";
import { usePermission } from "@/composables/permissions"

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps(['posts'])
const form = useForm({})

const showConfirmDeletePostModal = ref(false)
const { hasPermission } = usePermission();


const confirmDeletePost = () => {
    showConfirmDeletePostModal.value = true;
}

const closeModal = () => {
    showConfirmDeletePostModal.value = false;
}

const deletePost = (id) => {
    form.delete(route('posts.destroy', id), {
        onSuccess: () => closeModal()        
    });
}
</script>

<template>
    <Head title="Post Index" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between">
                        <div class="p-1 text-gray-900">Post Index Page</div>
                            <Link :href="route('posts.create')" class="px-3 py-2 text-xs text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">New Post</Link>
                    </div>
                    <div class="mt-6">
                        <Table>
                            <template #header>
                                <TableRow>
                                    <TableHeaderCell>ID</TableHeaderCell>
                                    <TableHeaderCell>Title</TableHeaderCell>
                                    <TableHeaderCell>Action</TableHeaderCell>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="post in posts" :key="post.id" class="border-b">
                                    <TableDataCell>{{ post.id }}</TableDataCell>
                                    <TableDataCell>{{ post.title }}</TableDataCell>
                                    <TableDataCell class="space-x-4">
                                            <Link :href="route('posts.edit', post.id)" class="text-green-400 hover:text-green-600">Edit</Link>
                                            <button @click="confirmDeletePost" class="text-red-400 hover:text-red-600">Delete</button>
                                            <Modal :show="showConfirmDeletePostModal" @close="closeModal">
                                                <div class="p-6">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                        Are you sure to delete this Post?
                                                    </h2>
                                                    <div class="mt-6 flex space-x-2">
                                                        <DangerButton @click="$event => deletePost(post.id)">Delete</DangerButton>
                                                        <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                                                    </div>
                                                </div>
                                            </Modal>
                                    </TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
