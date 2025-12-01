<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },

    class: {
        type: String,
        default: '',
    },
});

const defaultClass = 'border rounded-lg cursor-pointer dark:bg-gray-700 dark:hover:bg-gray-600 hover:opacity-90 transition-all dark:border dark:border-white';
const combinedClass = `${defaultClass} ${props.class}`.trim();

</script>
<template>
    <button :type="type" :class="combinedClass">
        <slot />
    </button>
</template>
