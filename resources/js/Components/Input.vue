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

const defaultClass = 'rounded-md border-2 border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-0 focus:border-cyan-700 focus:border-2';
const combinedClass = `${defaultClass} ${props.class}`.trim();

</script>

<template>
    <div class="flex flex-col w-full">
        <label v-if="label" class="text-sm font-bold text-gray-700 dark:text-white uppercase">
            {{ label }}
        </label>
        <input :type="type" :id="id" :placeholder="placeholder" :name="name" :class="combinedClass" v-model="model"
            v-bind="$attrs" />
    </div>
</template>
