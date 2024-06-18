<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    user: {
        type: Object,
    },
})

const findValue = (searchKey) => {
    if (props.user.details && Array.isArray(props.user.details)) {
        const item = props.user.details.find((item) => item.key === searchKey);
        return item ? item.value : null;
    } else {
        return null;
    }
}

const breadcrumbs = [
    {name: 'Users', routeName: 'users.index'},
    {name: `View profile ${props.user?.fullname}`, routeName: null}
]

</script>

<template>
    <Head title="View profile" />

    <AuthenticatedLayout>
        <Breadcrumbs :items="breadcrumbs"/>

        <div class="py-6">
            <div class="bg-white shadow-lg rounded-lg p-8">
                <div class="flex items-center justify-center">
                    <img
                        class="w-32 h-32 rounded-full object-cover border-4 border-blue-500"
                        :src="user.avatar"
                        :alt="user.fullname"
                    >
                </div>
                <div class="mt-6 text-center">
                    <h2 class="text-2xl font-semibold text-gray-800">
                       {{ user.prefixname }} {{ user.fullname }} {{ user.suffixname }}
                    </h2>
                    <p class="text-gray-600">
                       {{ user.email }}
                    </p>
                </div>
                <div class="mt-6">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800">Details</h3>
                        <p v-if="findValue('Full name')" class="text-gray-600 mt-2">
                            <span class="font-semibold">Full name:</span>
                            {{ findValue('Full name') }}
                        </p>
                        <p v-if="findValue('Middle Initial')" class="text-gray-600 mt-2">
                            <span class="font-semibold">Middle Initial:</span>
                            {{ findValue('Middle Initial') }}
                        </p>
                        <p v-if="findValue('Gender')" class="text-gray-600 mt-2">
                            <span class="font-semibold">Gender:</span>
                            {{ findValue('Gender') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
