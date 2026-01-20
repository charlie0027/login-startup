<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: 'Toggle',
    },
    enabledText: {
        type: String,
        default: 'Enabled',
    },
    disabledText: {
        type: String,
        default: 'Disabled',
    },
    color: {
        type: String,
        default: 'green', // Tailwind color for "on" state
    },
})

const emit = defineEmits(['update:modelValue'])

function toggle(event) {
    emit('update:modelValue', event.target.checked)
}
</script>

<template>
    <div class="text-gray-900 dark:text-white">
        <label class="inline-flex cursor-pointer relative">
            <!-- Hidden checkbox -->
            <input type="checkbox" :checked="modelValue" @change="toggle" class="sr-only peer" v-bind="$attrs" />

            <!-- Switch background -->
            <div class="w-11 h-6 bg-gray-300 rounded-full transition-colors duration-300"
                :class="modelValue ? `peer-checked:bg-${color}-600` : ''"></div>

            <!-- Switch knob -->
            <div
                class="absolute w-5 h-5 bg-white rounded-full left-0.5 top-0.5 transform transition-transform duration-300 peer-checked:translate-x-5">
            </div>

            <!-- Label -->
            <span class="ml-3 text-sm font-medium">
                {{ label }} ({{ modelValue ? enabledText : disabledText }})
            </span>
        </label>
    </div>
</template>
