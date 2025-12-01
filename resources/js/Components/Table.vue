<script setup>
import { Link } from '@inertiajs/vue3';
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
    }
})
</script>
<template>
    <div class="w-full overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <table class="border table-auto min-w-max lg:w-full bg-white dark:bg-gray-800">
                <thead class="bg-linear-to-r from-cyan-300 to-blue-500 dark:from-stone-700 dark:to-gray-400">
                    <th v-for="col in columns" :key="col.key" class="px-2 py-1 border">
                        {{ col.label }}
                    </th>
                </thead>
                <tbody>
                    <!-- If rows exist, render them -->
                    <tr v-for="row in rows" :key="row.id" v-if="rows.length > 0"
                        class="border px-2 py-1 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <td v-for="col in columns" :key="col.key" class="px-2 py-1 border pl-6 text-[12px]">
                            <slot name="table_td" :row="row" :col="col" :value="row[col.key]">
                                {{ row[col.key] }}
                            </slot>
                        </td>
                    </tr>

                    <!-- If no rows, show a single "No data" row -->
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
