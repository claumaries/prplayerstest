<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import {Head, useForm} from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {ref} from "vue";

defineProps({
    prefixes: {
        type: Object,
        required: true,
    }
})

const previewImage = ref('')

const form = useForm({
    redirectRoute: 'users.index',
    prefixname: '',
    firstname: '',
    middlename: '',
    lastname: '',
    suffixname: '',
    username: '',
    photo: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const uploadImage = (e) => {
    if (e.target?.files?.length > 0) {
        const image = e.target.files[0]
        form.photo = image

        const reader = new FileReader()
        reader.readAsDataURL(image)
        reader.onload = e => {
            previewImage.value = e.target.result
        };
    }
}

const createUser = () => {
    form.post(route('users.store'), {
        onSuccess: () => form.reset(),
    })
}

const breadcrumbs = [
    {name: 'Users', routeName: 'users.index'},
    {name: 'Create user', routeName: null},
];

</script>

<template>
    <Head title="Create user" />

    <AuthenticatedLayout>
        <Breadcrumbs :items="breadcrumbs"/>

        <div class="py-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create user
            </h2>
            <form @submit.prevent="createUser">
            <div class="grid grid-cols-12 gap-5 mt-5">
                <div class="col-span-12 lg:col-span-7 bg-gray-50 border-2 border-gray-100 rounded-md">
                    <div class="p-5">
                        <div class="p-3">
                            <div class="lg:w-1/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Prefix
                            </div>
                            <div class="mt-5">
                                <select class="mt-1 block w-full sm:w-1/4" v-model="form.prefixname" required autofocus>
                                    <option value="">Select a prefix</option>
                                    <option v-for="prefix in prefixes" :key="prefix" :value="prefix">
                                        {{ prefix }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.prefixname" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Photo
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    @change="uploadImage"
                                    class="block mb-5 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    type="file"
                                />
                                <InputError :message="form.errors.photo" class="mt-2" />
                            </div>
                            <img v-if="previewImage" :src="previewImage" alt="photo" class="w-48"/>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                First name*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="text"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.firstname"
                                    required
                                    autocomplete="firstname"
                                />
                                <InputError :message="form.errors.firstname" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Middle name
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="text"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.middlename"
                                    autocomplete="middlename"
                                />
                                <InputError :message="form.errors.middlename" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Last name*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="text"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.lastname"
                                    required
                                    autocomplete="lastname"
                                />
                                <InputError :message="form.errors.lastname" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Email*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="email"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="email"
                                />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Username*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="text"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.username"
                                    required
                                    autocomplete="username"
                                />
                                <InputError :message="form.errors.username" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Suffix
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="text"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.suffixname"
                                    autocomplete="suffixname"
                                />
                                <InputError :message="form.errors.suffixname" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Password*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="password"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="password"
                                />
                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="lg:w-3/4 w-full font-medium flex items-center border-b border-gray-300 pb-2">
                                Confirm Password*
                            </div>
                            <div class="mt-5">
                                <TextInput
                                    type="password"
                                    class="mt-1 block lg:w-3/4 w-full"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="password_confirmation"
                                />
                                <InputError :message="form.errors.password_confirmation" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-5 h-[200px] p-5 bg-gray-50 border-2 border-gray-100 rounded-md">
                    <div class="flex items-center mb-4">
                        <input
                            v-model="form.redirectRoute"
                            value="users.index"
                            id="action-2"
                            type="radio"
                            class="w-4 h-4 text-blue-600 border-gray-500 focus:ring-blue-500 focus:ring-2"
                        >
                        <label for="action-2" class="ms-2 text-l font-medium text-gray-900">
                            Back to list
                        </label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input
                            v-model="form.redirectRoute"
                            value="users.create"
                            id="action-1"
                            type="radio"
                            class="w-4 h-4 text-blue-600 border-gray-500 focus:ring-blue-500 focus:ring-2"
                        >
                        <label for="action-1" class="ms-2 text-l font-medium text-gray-900">
                            Stay on page
                        </label>
                    </div>
                    <div class="mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
