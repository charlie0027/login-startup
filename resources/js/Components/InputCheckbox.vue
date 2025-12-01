<script setup>
const props = defineProps({
    modelValue: Array,
    value: [String, Number],
    label: String,
    id: String,
    name: String,
    type: {
        type: String,
        default: 'checkbox',
    },
});

const emit = defineEmits(['update:modelValue']);

function toggleCheckbox(event) {
    const checked = event.target.checked;
    const current = Array.isArray(props.modelValue) ? props.modelValue : [];
    const updated = checked
        ? [...current, props.value]
        : current.filter(val => val !== props.value);
    emit('update:modelValue', updated);
}
</script>

<template>
    <div class="flex items-center space-x-2  cursor-pointer">
        <input :type="type" :id="id" :name="name" :value="value"
            :checked="Array.isArray(modelValue) && modelValue.includes(value)" @change="toggleCheckbox"
            class="cursor-pointer" />
        <label :for="id" class="text-sm font-medium text-gray-700 dark:text-white  cursor-pointer">
            {{ label }}
        </label>
    </div>
</template>
