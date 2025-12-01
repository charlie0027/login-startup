<script setup>
const props = defineProps({
    class: {
        type: String,
        default: '',
    },
});
const defaultClass = 'text-red-600 text-xs mt-1 mb-1';
const combinedClass = `${defaultClass} ${props.class}`.trim();
</script>
<template>
    <div :class="combinedClass">
        <slot />
    </div>
</template>
