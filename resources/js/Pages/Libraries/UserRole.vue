<script setup>
import MainLayout from '../../Pages/Layouts/MainLayout.vue'
import { ref, watch, computed } from 'vue';
import { useForm, router, Head, usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";
import debounce from 'lodash/debounce'

const page = usePage()
const can = page.props.can || {}

const toast = useToast()
const props = defineProps({
    user_roles: Object,
    filters: String,
    filters_permission: String,
    permissions: Object,
    allPermissions: Object,
})

const form = useForm({
    id: '',
    role_name: '',
    role_code: '',
    description: '',
    permissions: [],
    is_default: '',
    status: '',
})

const formPermission = useForm({
    id: '',
    name: '',
    description: '',
})

// Create
const addRole = ref(false)
const openAddModal = () => {
    form.reset()
    addRole.value = true
}

// Create
const addPermission = ref(false)
const openAddPermissionModal = () => {
    formPermission.reset()
    addPermission.value = true
}

// Read/Table
const columns = [
    { key: 'id', label: 'ID' },
    { key: 'role_name', label: 'Name' },
    { key: 'role_code', label: 'Code' },
    { key: 'status', label: 'Status' },
    { key: 'is_default', label: 'Default' },
    { key: 'permissions', label: 'Permissions' },
    { key: 'action', label: 'Actions' }
]

// Read/Table - Permissions
const columns_permission = [
    // { key: 'id', label: 'ID' },
    { key: 'description', label: 'Description' },
    // { key: 'allowed_roles', label: 'Allowed Roles' },
    { key: 'name', label: 'Name' },
    { key: 'action', label: 'Actions' }
]

// Search
const searchInput = ref(props.filters)
watch(searchInput, debounce(value => {
    router.get('/libraries/user_roles', { searchInput: value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 300))

// Search Permission
const searchInputPermission = ref(props.filters_permission)
watch(searchInputPermission, debounce(value => {
    router.get('/libraries/user_roles', { searchInputPermission: value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 300))

// Edit
const editRole = ref(false)
const openEditModal = (user_role) => {
    form.id = user_role.id
    form.role_code = user_role.role_code
    form.role_name = user_role.role_name
    form.description = user_role.description
    form.status = user_role.status
    form.is_default = user_role.is_default
    form.permissions = user_role.permissions.map(p => p.id)

    editRole.value = true
}

// Edit Permission
const editPermission = ref(false)
const openEditPermissionModal = (permission) => {
    formPermission.id = permission.id
    formPermission.name = permission.name
    formPermission.description = permission.description
    editPermission.value = true
}

const confirmDeactivate = ref(false)
const openActivDeactivateModal = (user_role) => {
    form.id = user_role.id
    form.role_code = user_role.role_code
    form.role_name = user_role.role_name
    form.status = user_role.status

    confirmDeactivate.value = true
}

// Delete Permission
const confirmDeletePermission = ref(false)
const openDeletePermissionModal = (permission) => {
    formPermission.id = permission.id
    formPermission.name = permission.name

    confirmDeletePermission.value = true
}


// tabs
const tabs = [
    { name: 'User Role', key: 'user_role' },
    { name: 'Permissions', key: 'permissions' }
]

const tabParam = new URLSearchParams(page.url.split('?')[1] || '').get('tab')
const currentTab = tabParam || (tabs.length > 0 ? tabs[0].key : null)

</script>
<template>

    <MainLayout>
        <div v-if="can.libraries_user_roles">
            <Tabs :tabs="tabs" :initial="currentTab">
                <template #user_role>

                    <Head title="Libraries - User Roles"></Head>
                    <div>
                        <div class="flex justify-between">
                            <h1 class="text-3xl font-bold mb-4">User Roles Library</h1>
                            <!-- Add Button -->
                            <ButtonCom v-if="can.libraries_user_roles_create" @click="openAddModal"
                                class="mb-4 border bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2">
                                <i class="fa fa-plus-circle"></i><span class="hidden md:inline  ml-4">Add New
                                    Role</span>
                            </ButtonCom>
                        </div>
                        <div class="mb-4">
                            <!-- Search -->
                            <form action="">
                                <Input type="text" id="searchText" placeholder="Search for Name or Code here..."
                                    v-model="searchInput"></Input>
                            </form>
                        </div>

                        <!-- <FlashMessage></FlashMessage> -->
                        <Table :columns="columns" :rows="props.user_roles.data" :total="props.user_roles.total">
                            <!-- TR -->
                            <template v-slot:table_tr></template>
                            <!-- TD -->
                            <template v-slot:table_td="{ row, col }">
                                <template v-if="col.key === 'action'">
                                    <div class="flex space-x-2"
                                        v-if="can.libraries_user_roles_update || can.libraries_user_roles_delete">
                                        <ButtonSmall v-if="can.libraries_user_roles_update"
                                            class="bg-blue-500 hover:bg-blue-700 text-white"
                                            @click="openEditModal(row)">
                                            <i class="fa fa-pencil-square"></i><span
                                                class="hidden md:inline">Edit</span>
                                        </ButtonSmall>
                                        <div v-if="can.libraries_user_roles_delete">
                                            <ButtonSmall v-if="row.status === 1"
                                                class="bg-red-500 hover:bg-red-600 text-white"
                                                @click="openActivDeactivateModal(row)">
                                                <i class="fa fa-trash"></i><span
                                                    class="hidden md:inline">Deactivate</span>
                                            </ButtonSmall>
                                            <ButtonSmall v-else class="bg-green-500 hover:bg-green-600 text-white"
                                                @click="openActivDeactivateModal(row)">
                                                <i class="fa fa-toggle-on"></i><span
                                                    class="hidden md:inline">Activate</span>
                                            </ButtonSmall>
                                        </div>
                                    </div>
                                    <div v-else>
                                        N/A
                                    </div>
                                </template>
                                <template v-if="col.key === 'status'">
                                    <span v-if="row.status === 1"
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 inset-ring inset-ring-green-600/20">
                                        Active</span>
                                    <span v-else
                                        class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 inset-ring inset-ring-red-600/10">
                                        Inactive</span>
                                </template>
                                <template v-if="col.key === 'is_default'">
                                    <span v-if="row.is_default === 1"
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 inset-ring inset-ring-green-600/20">
                                        Yes</span>
                                    <span v-else
                                        class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 inset-ring inset-ring-red-600/10">
                                        No</span>
                                </template>
                                <template v-if="col.key === 'permissions'">
                                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300">
                                        <li v-for="perm in row.permissions" :key="perm">
                                            {{ perm.description || 'Permissions Not Set' }}
                                        </li>
                                    </ul>
                                </template>
                            </template>
                        </Table>
                        <Paginator :rows="props.user_roles.links"></Paginator>
                    </div>
                </template>

                <template #permissions>

                    <Head title="Libraries - Permissions"></Head>
                    <div>
                        <div class="flex justify-between">
                            <h1 class="text-3xl font-bold mb-4">Permissions Library</h1>
                            <!-- Add Button -->
                            <ButtonCom v-if="can.libraries_user_roles_create" @click="openAddPermissionModal"
                                class="mb-4 border bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2">
                                <i class="fa fa-plus-circle"></i><span class="hidden md:inline  ml-4">Add New
                                    Permission</span>
                            </ButtonCom>
                        </div>
                        <div class="mb-4">
                            <!-- Search -->
                            <form action="">
                                <Input type="text" id="searchText" placeholder="Search for Name or Description here..."
                                    v-model="searchInputPermission"></Input>
                            </form>
                        </div>

                        <!-- <FlashMessage></FlashMessage> -->
                        <Table :columns="columns_permission" :rows="props.permissions.data"
                            :total="props.permissions.total">
                            <!-- TR -->
                            <template v-slot:table_tr></template>
                            <!-- TD -->
                            <template v-slot:table_td="{ row, col }">
                                <template v-if="col.key === 'action'">
                                    <div class="flex space-x-2"
                                        v-if="can.libraries_user_roles_update || can.libraries_user_roles_delete">
                                        <ButtonSmall v-if="can.libraries_user_roles_update"
                                            class="bg-blue-500 hover:bg-blue-700 text-white"
                                            @click="openEditPermissionModal(row)">
                                            <i class="fa fa-pencil-square"></i><span
                                                class="hidden md:inline">Edit</span>
                                        </ButtonSmall>

                                        <ButtonSmall v-if="can.libraries_user_roles_delete"
                                            class="bg-red-500 hover:bg-red-600 text-white"
                                            @click="openDeletePermissionModal(row)">
                                            <i class="fa fa-trash"></i><span class="hidden md:inline">Delete</span>
                                        </ButtonSmall>

                                    </div>
                                    <div v-else>
                                        N/A
                                    </div>
                                </template>
                            </template>
                        </Table>
                        <Paginator :rows="props.permissions.links"></Paginator>
                    </div>
                </template>
            </Tabs>
        </div>
        <div v-else>
            <div class="flex justify-center mt-8 text-red-600 dark:text-red-400">
                <h1>Access Denied. Please contact your Administrator</h1>
            </div>
        </div>

    </MainLayout>

    <!-- Add Modal -->
    <Modal v-model="addRole">
        <template #modal_header>Add Role</template>
        <template #modal_body>
            <form @submit.prevent="form.post('/libraries/user_roles', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form.reset()
                        addRole = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="grid grid-cols-10 gap-4 mb-4">
                    <div class="col-span-8">
                        <Input type="text" id="role_name" v-model="form.role_name" label="Name"></Input>
                        <Error v-if="form.errors.role_name">{{ form.errors.role_name }}</Error>
                    </div>
                    <div class="col-span-2">
                        <Input type="text" id="role_code" v-model="form.role_code" label="Code"></Input>
                        <Error v-if="form.errors.role_code">{{ form.errors.role_code }}</Error>
                    </div>
                </div>
                <div class="mb-4">
                    <InputTextarea id="description" v-model="form.description" label="Description"></InputTextarea>
                    <Error v-if="form.errors.description">{{ form.errors.description }}</Error>
                </div>
                <div class="mb-4">
                    <label class="text-sm font-bold text-gray-700 dark:text-white uppercase">
                        Permissions
                    </label>
                    <div class="px-2.5 pb-5 mt-4 bg-gray-500/10 rounded-lg">
                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Users
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('libraries_users'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                User Roles and Permissions
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('libraries_user_roles'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Settings
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('settings_setting'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Archives
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('archives_archives'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Audit Trails
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('archives_audit_trails'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                User Summary
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('reports_user_summary'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                    </div>
                    <Error v-if="form.errors.permissions">{{ form.errors.permissions }}</Error>
                </div>
                <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Add Role</ButtonCom>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Add Permission Modal -->
    <Modal v-model="addPermission">
        <template #modal_header>Add Permission</template>
        <template #modal_body>
            <form @submit.prevent="formPermission.post('/libraries/permission', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        formPermission.reset()
                        addPermission = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="mb-4">
                    <Input type="text" id="name" v-model="formPermission.name" label="Name"></Input>
                    <Error v-if="formPermission.errors.name">{{ formPermission.errors.name }}</Error>

                </div>
                <div class="mb-4">
                    <InputTextarea id="description" v-model="formPermission.description" label="Description">
                    </InputTextarea>
                    <Error v-if="formPermission.errors.description">{{ formPermission.errors.description }}</Error>
                </div>

                <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Add Permission
                    </ButtonCom>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Edit -->
    <Modal v-model="editRole">
        <template #modal_header>Edit Role</template>
        <template #modal_body>
            <form @submit.prevent="form.put('/libraries/user_roles/' + form.id + '/update', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form.reset()
                        editRole = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="md:grid md:grid-cols-10 md:gap-4 mb-4">
                    <div class="md:col-span-8 mb-4">
                        <Input type="text" id="role_name" v-model="form.role_name" label="Name"></Input>
                        <Error v-if="form.errors.role_name">{{ form.errors.role_name }}</Error>
                    </div>
                    <div class="md:col-span-2">
                        <Input type="text" id="role_code" v-model="form.role_code" label="Code"></Input>
                        <Error v-if="form.errors.role_code">{{ form.errors.role_code }}</Error>
                    </div>
                </div>
                <div class="mb-4">
                    <InputTextarea id="description" v-model="form.description" label="Description"></InputTextarea>
                    <Error v-if="form.errors.description">{{ form.errors.description }}</Error>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-4 mb-4">
                    <div class="mb-4">
                        <SelectInput label="Status" v-model="form.status">
                            <template #options>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </template>

                        </SelectInput>
                        <Error v-if="form.errors.status">{{ form.errors.status }}</Error>
                    </div>
                    <div>
                        <SelectInput label="Default" v-model="form.is_default">
                            <template #options>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </template>

                        </SelectInput>
                        <Error v-if="form.errors.is_default">{{ form.errors.is_default }}</Error>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="text-sm font-bold text-gray-700 dark:text-white uppercase">
                        Permissions
                    </label>
                    <div class="px-2.5 pb-5 mt-4 bg-gray-500/10 rounded-lg">
                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Users
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('libraries_users'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                User Roles and Permissions
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('libraries_user_roles'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Settings
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('settings_setting'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Archives
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('archives_archives'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                Audit Trails
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('archives_audit_trails'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-2 px-2.5">
                            <label
                                class="text-sm font-bold text-gray-700 dark:text-white uppercase col-span-2 md:col-span-3 mt-4">
                                User Summary
                            </label>
                            <div v-for="permission in props.allPermissions.filter(p => p.name.startsWith('reports_user_summary'))"
                                :key="permission.id" class="flex items-center space-x-2">
                                <InputCheckbox type="checkbox" :label="permission.description"
                                    :id="`permission-${permission.id}`" v-model="form.permissions"
                                    :value="permission.id" />
                            </div>
                        </div>

                    </div>
                    <Error v-if="form.errors.permissions">{{ form.errors.permissions }}</Error>
                </div>
                <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Update Role</ButtonCom>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Edit Permission Modal -->
    <Modal v-model="editPermission">
        <template #modal_header>Edit Permission</template>
        <template #modal_body>
            <form @submit.prevent="formPermission.put('/libraries/permission/' + formPermission.id + '/update', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        formPermission.reset()
                        editPermission = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <Input type="hidden" v-model="formPermission.id" />
                <div class="mb-4">
                    <Input type="text" id="name" v-model="formPermission.name" label="Name"></Input>
                    <Error v-if="formPermission.errors.name">{{ formPermission.errors.name }}</Error>
                </div>

                <div class="mb-4">
                    <InputTextarea id="description" v-model="formPermission.description" label="Description">
                    </InputTextarea>
                    <Error v-if="formPermission.errors.description">{{ formPermission.errors.description }}</Error>
                </div>

                <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Update Permission
                    </ButtonCom>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Deactivate -->
    <ModalConfirm v-model="confirmDeactivate">
        <template #modal_header>
            Deactivate Role?
        </template>
        <template #modal_body>
            <span>Name: {{ form.role_name }} [ {{ form.role_code }}]</span>
            <div class="flex justify-end items-center space-x-4 mb-4">
                <form @submit.prevent="form.put('/libraries/user_roles/' + form.id + '/deactivate', {
                    onSuccess: () => {
                        if ($page.props.flash.success) {
                            toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                            form.reset()
                            confirmDeactivate = false
                        } else if ($page.props.flash.error) {
                            toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                        }
                    },

                    onError: () => {
                        toast.error('Something went wrong, please try again later', { icon: 'fas fa-exclamation-circle' })
                    }
                })">
                    <div>
                        <Input type="text" id="id" class="hidden" v-model="form.id"></Input>
                        <Input type="text" id="id" class="hidden" v-model="form.status"></Input>
                    </div>
                    <div>
                        <ButtonCom v-if="form.status === 1" type="submit"
                            class="bg-white text-red-500 px-4 py-2 border border-red-500 dark:border-red-500">Deactivate
                        </ButtonCom>
                        <ButtonCom v-else type="submit"
                            class="bg-white text-green-500 px-4 py-2 border border-green-500 dark:border-green-500">
                            Activate
                        </ButtonCom>
                    </div>

                </form>
                <ButtonCom type="submit" class="bg-blue-500 text-white px-4 py-2 border"
                    @click="confirmDeactivate = false">
                    Cancel
                </ButtonCom>
            </div>

        </template>
    </ModalConfirm>

    <!-- Delete Permission -->
    <ModalConfirm v-model="confirmDeletePermission">
        <template #modal_header>
            Delete Permission?
        </template>
        <template #modal_body>
            <span>Name: {{ formPermission.name }}</span>
            <div class="flex justify-end items-center space-x-4 mb-4">
                <form @submit.prevent="formPermission.delete('/libraries/permission/' + formPermission.id + '/destroy', {
                    onSuccess: () => {
                        if ($page.props.flash.success) {
                            toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                            formPermission.reset()
                            confirmDeletePermission = false
                        } else if ($page.props.flash.error) {
                            toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                        }
                    },

                    onError: () => {
                        toast.error('Something went wrong, please try again later', { icon: 'fas fa-exclamation-circle' })
                    }
                })">
                    <div>
                        <Input type="text" id="id" class="hidden" v-model="formPermission.id"></Input>
                    </div>
                    <div>
                        <ButtonCom type="submit"
                            class="bg-white text-red-500 px-4 py-2 border border-red-500 dark:border-red-500">
                            Delete
                        </ButtonCom>
                    </div>

                </form>
                <ButtonCom type="submit" class="bg-blue-500 text-white px-4 py-2 border"
                    @click="confirmDeletePermission = false">
                    Cancel
                </ButtonCom>
            </div>

        </template>
    </ModalConfirm>
</template>
