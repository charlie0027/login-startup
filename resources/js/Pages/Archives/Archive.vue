<script setup>
import MainLayout from '../Layouts/MainLayout.vue'
import { Head, useForm } from '@inertiajs/vue3';
import moment from 'moment';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const props = defineProps({
    deleted_users: Object,
})

const toast = useToast()

const col_tabs = [
    { name: 'Users', key: 'users' }
]

const col_table = [
    { key: 'username', label: 'Username' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'deleted_at', label: 'Date Deleted' },
    { key: 'actions', label: 'Actions' }
]

const form = useForm({
    id: '',
    username: ''
})

const restoreModal = ref(false)
const restore_user = (deleted_user) => {
    form.id = deleted_user.id
    form.username = deleted_user.username
    restoreModal.value = true
}

</script>
<template>
    <MainLayout>

        <Head title="Archive"></Head>
        <Tabs :tabs="col_tabs">
            <template #users>
                <div>
                    <h1 class="text-3xl font-bold mb-4">Removed Users</h1>
                </div>
                <Table :columns="col_table" :rows="props.deleted_users.data" :total="props.deleted_users.total">
                    <template #table_td="{ row, col }">
                        <template v-if="col.key === 'name'">
                            {{ row.last_name }}, {{ row.first_name }} {{ row.middle_name }} {{ row.extension_name }}
                        </template>
                        <template v-if="col.key === 'deleted_at'">
                            {{ moment(row.deleted_at).format('LLL') }}
                        </template>
                        <template v-if="col.key === 'actions'">
                            <div class="py-1">
                                <ButtonSmall class="bg-green-500 hover:bg-green-400" @click="restore_user(row)"><i
                                        class="fa fa-recycle"></i><span
                                        class="hidden md:inline-block ml-1">Restore</span></ButtonSmall>
                            </div>
                        </template>
                    </template>
                </Table>
                <Paginator :rows="props.deleted_users.links"></Paginator>
            </template>
        </Tabs>
    </MainLayout>
    <ModalConfirm v-model="restoreModal">
        <template #modal_header>
            Restore User
        </template>
        <template #modal_body>
            <div class="mb-4">
                <p>Are you sure you wanna restore this user? [username: {{ form.username }}]</p>
            </div>
            <div class="flex justify-end items-center space-x-4 mb-4">
                <form @submit.prevent="form.put('/archives/archives', {
                    onSuccess: () => {
                        toast.success($page.props.flash.success, { icon: 'fa fa-check-circle' })
                        restoreModal = false
                    },

                    onError: () => {
                        toast.error('Error. Something went wrong', { icon: 'fa fa-exclamation-circle' })
                    }
                })">
                    <Input type="hidden" v-model="form.id"></Input>
                    <Input type="hidden" v-model="form.username"></Input>
                    <ButtonCom type="submit"
                        class="py-1 px-3 bg-green-500 hover:bg-green-400 dark:bg-green-500 dark:hover:bg-green-400">
                        Confirm
                    </ButtonCom>
                </form>
                <ButtonCom @click="restoreModal = false" class="py-1 px-3">Cancel</ButtonCom>
            </div>
        </template>
    </ModalConfirm>
</template>
