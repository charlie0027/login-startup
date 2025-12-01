<script setup>

const model = defineModel()

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
    id: String,
    placeholder: String,
    label: String,
    name: String,
    class: {
        type: String,
        default: '',
    },
});

const defaultClass = 'w-full rounded-md border-2 border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-0 focus:border-cyan-700 focus:border-2 text-sm dark:bg-gray-800 text-gray-700 dark:text-white appearance-none pr-7';
const combinedClass = `${defaultClass} ${props.class}`.trim();

</script>

<template>
    <div class="relative w-full">
        <label v-if="label" class="w-25 text-sm font-bold text-gray-700 dark:text-white uppercase">
            {{ label }}
        </label>
        <select :id="id" :placeholder="placeholder" :name="name" :class="combinedClass" v-model="model" v-bind="$attrs">
            <slot name="options"></slot>
        </select>
        <!-- Custom arrow -->
        <span class="absolute right-2 md:right-1 top-11.5 -translate-y-1/2 pointer-events-none">
            <svg class="w-8 h-10 text-gray-500 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M7 7l3 3 3-3" />
            </svg>
        </span>
    </div>
</template>

<style></style>
