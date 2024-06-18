<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from "vue";

const props = defineProps({
    items: {
        type: Object,
        required: false,
        default: () => ({})
    }
})

const filteredLinks = computed(() => {
    return props.items?.links || [];
});

const hasLinks = computed(() => {
    return filteredLinks.value.length > 0 && props.items.total > props.items.per_page;
});
</script>

<template>
    <nav v-if="hasLinks" class="mt-4">
        <ul class="flex list-none pl-0">
            <li
                v-for="link in filteredLinks"
                :key="link.label"
                :class="{ 'opacity-50 cursor-not-allowed': !link.url, 'font-bold': link.active }" class="mx-1"
            >
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200 transition duration-150 ease-in-out"
                />

                <span v-else v-html="link.label" class="px-3 py-1 border border-gray-300 rounded"></span>
            </li>
        </ul>
    </nav>
</template>
