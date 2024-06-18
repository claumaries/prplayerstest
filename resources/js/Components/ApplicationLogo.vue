<script setup>
import {Link} from '@inertiajs/vue3'
</script>

<template>
    <Link :href="route('dashboard')">
        <img :src="$page.props.logoUrl" class="w-48 p-3" alt="logo"/>
    </Link>
</template>
