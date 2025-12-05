<script setup>
import { useTheme } from '@/MyJs/useTheme.js';
const props = defineProps({
    class: {
        type: String,
        default: '',
    },
});

const { isDark, toggleTheme } = useTheme()

const defaultClassCard = 'border-8 border-double border-blue-900 dark:border-white rounded-lg mx-auto bg-white sm:mx-0 dark:bg-gray-800 dark:text-white';
const combinedClassCard = `${defaultClassCard} ${props.class}`.trim();

</script>
<template>
    <div
        class="py-10 w-full h-full min-h-screen md:h-screen bg-linear-to-r from-cyan-300 to-blue-500 flex justify-center items-center dark:from-stone-700 dark:to-gray-400">

        <button @click="toggleTheme"
            class="absolute top-4 right-4 px-3 py-2 rounded text-white hover:border hover:border-black z-10 cursor-pointer">
            {{ isDark ? '🌞' : '🌙' }}
        </button>

        <div :class="combinedClassCard">
            <slot />
        </div>
    </div>
</template>
