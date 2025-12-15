<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
const props = defineProps({
    // Array of column definitions
    columns: {
        type: Array,
        required: true
    },
    // Array of row data
    rows: {
        type: Array,
        required: true
    },
    total: {
        type: Number,
        required: true,
    }
})

const sortKey = ref(null)
const sortDirection = ref('asc')

// computed rows sorted by sortKey
const sortedRows = computed(() => {
    if (!sortKey.value) return props.rows
    return [...props.rows].sort((a, b) => {
        const valA = a[sortKey.value]
        const valB = b[sortKey.value]
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1
        return 0
    })
})

function sortBy(key) {
    if (sortKey.value === key) {
        // toggle direction
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortKey.value = key
        sortDirection.value = 'asc'
    }
}


</script>
<template>
    <div class="w-full overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="flex mb-2">
                <span>Total: <span class="font-bold">{{ props.total }}</span></span>
            </div>
            <table class="border table-auto min-w-max lg:w-full bg-white dark:bg-gray-800">
                <thead>
                    <tr class="bg-blue-200 dark:bg-blue-600">
                        <th v-for="col in columns" :key="col.key" class="px-2 py-1 border cursor-pointer select-none"
                            @click="sortBy(col.key)">
                            {{ col.label }}
                            <!-- optional sort indicator -->
                            <span v-if="sortKey === col.key" class="text-[10px]">
                                {{ sortDirection === 'asc' ? '↑' : '↓' }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in sortedRows" :key="row.id" v-if="sortedRows.length > 0">
                        <td v-for="col in columns" :key="col.key" class="px-2 py-1 border pl-6 text-[12px]">
                            <slot name="table_td" :row="row" :col="col" :value="row[col.key]">
                                {{ row[col.key] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-else>
                        <td :colspan="columns.length" class="text-center py-4 text-gray-500 dark:text-gray-300">
                            No data available
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
