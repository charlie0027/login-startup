<script setup>
import MainLayout from '../Layouts/MainLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    summary_users: Object,

})

const page = usePage()
const can = page.props.can || {}

const col_tabs = [
    { key: 'user_summary', name: 'User Summary' }
]

const col_table = [
    { key: 'last_name', label: 'Name' },
    { key: 'username', label: 'Username' },
    { key: 'email', label: 'Email' },
    { key: 'roles', label: 'Role/s' },
    { key: 'permissions', label: 'Permission/s' }
]

</script>
<template>

    <Head title="Reports - User Summary"></Head>
    <MainLayout>
        <div v-if="can.reports_user_summary">
            <Tabs :tabs="col_tabs">
                <template #user_summary>

                    <Head title="Reports - User Summary"></Head>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-3xl font-bold">User Summary</h1>
                            <div class="flex space-x-2">
                                <form v-if="can.reports_user_summary_export" method="GET"
                                    action="/reports/export_user_summary_pdf" target="_blank">
                                    <ButtonCom type="submit" class="py-1 px-3 text-center hover:bg-gray-300">
                                        <i class="fa fa-file-pdf text-red-600"></i><span
                                            class="hidden md:inline-block ml-2">PDF</span>
                                    </ButtonCom>
                                </form>

                                <form v-if="can.reports_user_summary_export" method="GET"
                                    action="/reports/export_user_summary_excel">
                                    <ButtonCom type="submit" class="py-1 px-3 text-center hover:bg-gray-300">
                                        <i class="fa fa-file-excel text-green-600"></i>
                                        <span class="hidden md:inline-block ml-2">Excel</span>
                                    </ButtonCom>
                                </form>

                                <form v-if="can.reports_user_summary_export" method="GET"
                                    action="/reports/export_user_summary_word" target="_blank">
                                    <ButtonCom type=" submit" class="py-1 px-3 text-center hover:bg-gray-300">
                                        <i class=" fa fa-file-word text-blue-600"></i><span
                                            class="hidden md:inline-block ml-2">Word</span>
                                    </ButtonCom>
                                </form>
                            </div>
                        </div>
                    </div>

                    <Table :columns="col_table" :rows="props.summary_users.data" :total="props.summary_users.total">
                        <template #table_td="{ col, row }">
                            <template v-if="col.key === 'roles'">
                                {{row.user_detail?.roles?.map(r => r.role_name).join(', ') || 'Role Not Set'}}
                            </template>
                            <template v-if="col.key === 'permissions'">
                                <div class="permissions-text">
                                    {{row.user_detail?.roles
                                        ?.flatMap(r => r.permissions || [])
                                        .map(p => p.description)
                                        .join(', ')
                                        || 'Role Not Set'}}
                                </div>
                            </template>
                        </template>
                    </Table>
                    <Paginator :rows="props.summary_users.links"></Paginator>
                </template>
            </Tabs>
        </div>
        <div v-else>
            <div class="flex justify-center mt-8 text-red-600 dark:text-red-400">
                <h1>Access Denied. Please contact your Administrator</h1>
            </div>
        </div>
    </MainLayout>
</template>
