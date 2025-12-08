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

const defaultClass = 'border border-white rounded-lg cursor-pointer transition-all dark:border text-[12px] border dark:text-white font-semibold py-1 px-2';
const combinedClass = `${props.class} ${defaultClass} `.trim();

</script>
<template>
    <button :type="type" :class="combinedClass">
        <slot />
    </button>
</template>
