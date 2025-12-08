<script setup>
import MainLayout from '../Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3';
import moment from 'moment';

const props = defineProps({
    audit_trails: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'user_id', label: 'User' },
    { key: 'action', label: 'Action' },
    { key: 'entity_type', label: 'Entity Type' },
    { key: 'entity_id', label: 'Entity ID' },
    { key: 'from_values_l', label: 'From' },
    { key: 'to_values_l', label: 'To' },
    { key: 'created_at_l', label: 'Action Taken ' }
]
</script>
<template>
    <MainLayout>

        <Head title="Audit Trails"></Head>
        <div>
            <div class="flex justify-between">
                <h1 class="text-3xl font-bold mb-4">Audit Trails Library</h1>
                <!-- <ButtonCom v-if="can.updateUserDetails" @click="addUser = true"
                    class="mb-4 border bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2">
                    <i class="fa fa-plus-circle"></i><span class="hidden md:inline  ml-4">Add New User</span>
                </ButtonCom> -->
            </div>
            <!-- <div class="mb-4">
                <form action="">
                    <Input type="text" id="searchText" placeholder="Search here..." v-model="searchInput"></Input>
                </form>
            </div> -->

            <!-- <FlashMessage></FlashMessage> -->
            <Table :columns="columns" :rows="props.audit_trails.data">
                <!-- TR -->
                <template v-slot:table_tr></template>
                <!-- TD -->
                <template v-slot:table_td="{ row, col }">

                    <template v-if="col.key === 'user_id'">
                        <strong class="uppercase">{{ row.user.last_name }}, {{ row.user.first_name }}
                            {{ row.user.middle_name }} {{ row.user.name_extension }}</strong>
                    </template>



                    <template v-else>
                        {{ row[col.key] }}
                    </template>

                    <template v-if="col.key === 'from_values_l'">
                        <div class="">
                            <details v-if="row.to_values && Object.keys(row.to_values).length > 0">
                                <summary class="cursor-pointer truncate">
                                    See original
                                </summary>
                                <li v-for="(value, key) in row.from_values" :key="key">
                                    <span>{{ key }} : {{ value }}</span>
                                </li>
                            </details>
                            <p v-else>N/A</p>
                        </div>
                    </template>
                    <template v-if="col.key === 'to_values_l'">
                        <div class="">
                            <details v-if="row.to_values && Object.keys(row.to_values).length > 0">
                                <summary class="cursor-pointer truncate">
                                    See changes
                                </summary>
                                <li v-for="(value, key) in row.to_values" :key="key">
                                    <span>{{ key }} : {{ value }}</span>
                                </li>
                            </details>
                            <p v-else>N/A</p>
                        </div>
                    </template>
                    <template v-if="col.key === 'created_at_l'">
                        {{ moment(row.created_at).calendar() }}
                    </template>

                </template>
            </Table>
            <Paginator :rows="props.audit_trails.links"></Paginator>
        </div>
    </MainLayout>
</template>
