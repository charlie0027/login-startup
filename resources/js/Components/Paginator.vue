<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    rows: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <div class="flex space-x-2 mt-4 w-full border p-2 items-center text-base">
        <template v-for="link in rows">
            <Link v-if="link.url" :href="link.url" v-html="link.label" :class="[
                'transition',
                link.active ? 'text-blue-500 dark:text-blue-500' : 'text-black dark:text-white',
            ]" preserve-scroll />
            <span v-else v-html="link.label" class="text-gray-500 cursor-not-allowed" />
        </template>
    </div>
</template>
