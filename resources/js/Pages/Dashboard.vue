<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <div class="bg-white p-6 rounded-lg shadow-lg mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold">Card 1</h3>
                        <p>Content for card 1</p>
                    </div>
                    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold">Card 2</h3>
                        <p>Content for card 2</p>
                    </div>
                    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold">Card 3</h3>
                        <p>Content for card 3</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
