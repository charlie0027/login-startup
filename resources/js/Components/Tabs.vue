<script setup>
import { ref } from 'vue'

const props = defineProps({
    tabs: {
        type: Array, // should be an array of objects
        required: true,
    },
    initial: {
        type: String,
        default: null
    }
})

const activeTab = ref(props.initial || (props.tabs[0]?.key ?? null))

function setActive(tabKey) {
    activeTab.value = tabKey
}
</script>

<template>
    <div class="w-full">
        <!-- Tab headers -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 ">
            <template v-for="tab in props.tabs" :key="tab.key">
                <button @click="setActive(tab.key)"
                    class="px-4 py-2 text-sm font-medium focus:outline-none transition-colors cursor-pointer uppercase"
                    :class="[
                        activeTab === tab.key
                            ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-300'
                            : 'text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-300'
                    ]">
                    {{ tab.name }}
                </button>
            </template>
        </div>

        <!-- Tab content -->
        <div class="p-4">
            <template v-for="tab in props.tabs" :key="tab.key">
                <div v-if="activeTab === tab.key">
                    <slot :name="tab.key">

                    </slot>
                </div>
            </template>
        </div>
    </div>
</template>
