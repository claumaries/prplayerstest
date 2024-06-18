<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head, Link, useForm} from '@inertiajs/vue3'
import Breadcrumbs from "@/Components/Breadcrumbs.vue"
import SimplePagination from "@/Components/SimplePagination.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {
    TrashIcon,
    ArrowUturnLeftIcon
} from "@heroicons/vue/24/solid/index.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";

defineProps({
    users: {
        type: Object,
        required: true,
    }
})

const confirmingUserDeletion = ref(false)

const form = useForm({
    userId: null,
})
const confirmUserDeletion = (userId) => {
    form.userId = userId
    confirmingUserDeletion.value = true
}

const deleteUser = () => {
    form.delete(route('users.delete'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
}

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
}

const restoreUser = (userId) => {
    form.userId = userId

    form.patch(route('users.restore'), {
        preserveScroll: true,
        onFinish: () => form.reset(),
    });
}

const breadcrumbs = [{
    name: 'Trashed users',
    routeName: null
}]

</script>

<template>
    <Head title="Trashed users" />

    <AuthenticatedLayout>
        <Breadcrumbs :items="breadcrumbs"/>

        <div class="py-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                Trashed user list
            </h2>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Username
                            </th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="users?.data?.length" v-for="user in users.data" class="odd:bg-white even:bg-gray-50 border-b">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap ">
                                <img class="w-10 h-10 rounded-full" :src="user.avatar" :alt="user.fullname">
                                <div class="ps-3">
                                    <div class="text-base font-semibold">
                                        <Link :href="route('users.show', [{id: user.id}]) ">
                                            {{ user.fullname }}
                                        </Link>
                                    </div>
                                    <div class="font-normal text-gray-500">
                                        {{ user.email }}
                                    </div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                {{ user.username }}
                            </td>
                            <td class="px-2 py-4 text-center">
                                <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:space-x-2 justify-center">
                                    <PrimaryButton @click="restoreUser(user.id)" class="w-full sm:w-auto">
                                        <ArrowUturnLeftIcon class="inline-block size-4 mr-1"/> Restore user
                                    </PrimaryButton>

                                    <DangerButton @click="confirmUserDeletion(user.id)" class="w-full sm:w-auto">
                                        <TrashIcon class="inline-block size-4 mr-1"/> Delete user
                                    </DangerButton>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <SimplePagination :items="users" />
            </div>

            <Modal :show="confirmingUserDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete the user?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Once the user is deleted, all of its resources and data will be permanently deleted.
                    </p>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ms-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            Delete User
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
