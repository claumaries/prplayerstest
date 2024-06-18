<script setup>
import { Link } from '@inertiajs/vue3';
import {
    HomeIcon,
    ChevronRightIcon,
} from '@heroicons/vue/24/solid'

const props = defineProps({
    items: {
        type: Object,
        required: false,
        default: () => ({})
    }
})

</script>

<template>
    <nav v-if="items.length" class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600"
                >
                    <HomeIcon class="inline-block size-4 mr-1"/> Dashboard
                </Link>
            </li>
            <li v-for="item in items" :key="item.name">
                <div class="flex items-center">
                  <ChevronRightIcon class="inline-block size-4 mr-1"/>
                    <Link v-if="item.routeName"
                        :href="route(item.routeName)"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600"
                    >
                        {{ item.name }}
                    </Link>
                    <span v-else class="text-sm font-medium text-gray-700 ">
                          {{ item.name }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>
</template>
