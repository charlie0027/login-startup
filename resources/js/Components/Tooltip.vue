<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    icon: {
        type: String,
        default: 'info-circle'
    }

})

</script>
<template>
    <div class="relative group inline-block">
        <i :class="['fa', `fa-${icon}`, 'text-sm', 'hover:text-gray-300']">
        </i>
        <div
            class="absolute z-10 hidden group-hover:block bg-gray-800 text-white text-sm rounded p-2 w-64 top-full left-0 mt-2 shadow-lg dark:bg-white dark:text-black">
            <slot />


        </div>
    </div>
</template>
