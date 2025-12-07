<script setup>
import MainLayout from '../Layouts/MainLayout.vue';
import { ref, watch, computed, onMounted } from 'vue';
import { useForm, router, Head, usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";
import debounce from 'lodash/debounce';
import axios from 'axios';

const page = usePage()
const can = page.props.can || {}


const props = defineProps({
    users: Object,
    filters: Object,
    citymuns: Array,
    provinces: Array,
    roles: Object
})

const toast = useToast()

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'username', label: 'Username' },
    { key: 'email', label: 'Email' },
    { key: 'action', label: 'Action' }
]

// Search
const searchInput = ref(props.filters)
watch(searchInput, debounce(value => {
    router.get('/libraries/users', { searchInput: value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 300))


// Add User
const addUser = ref(false)
const form = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: '',
    name_extension: '',
});

// Edit User
const editUser = ref(false)
const selectedUser = ref(null)
const form_edit = useForm({
    id: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    username: '',
    name_extension: '',
    user_detail: {
        birthdate: '',
        gender: '',
        contact: '',
        house_number: '',
        street: '',
        barangay: '',
        citymun: '',
        province: '',
        id_number: ''
    }
})

const openEditModal = (user) => {
    selectedUser.value = user

    form_edit.id = user.id
    form_edit.first_name = user.first_name
    form_edit.middle_name = user.middle_name
    form_edit.last_name = user.last_name
    form_edit.email = user.email
    form_edit.username = user.username
    form_edit.name_extension = user.name_extension
    form_edit.user_detail.birthdate = user.user_detail?.birthdate ?? ''
    form_edit.user_detail.gender = user.user_detail?.gender ?? ''
    form_edit.user_detail.contact = user.user_detail?.contact ?? ''
    form_edit.user_detail.house_number = user.user_detail?.house_number ?? ''
    form_edit.user_detail.street = user.user_detail?.street ?? ''
    form_edit.user_detail.id_number = user.user_detail?.id_number ?? ''
    form_edit.user_detail.barangay = user.user_detail?.barangay ?? ''
    form_edit.user_detail.citymun = user.user_detail?.citymun && props.citymuns.some(c => c.citymunCode === user.user_detail.citymun)
        ? user.user_detail.citymun
        : ''
    form_edit.user_detail.province = user.user_detail?.province &&
        props.provinces.some(p => p.provCode === user.user_detail.province)
        ? user.user_detail.province
        : ''

    editUser.value = true
}

const filteredCitymuns = computed(() => {
    if (!form_edit.user_detail.province) return []
    return props.citymuns.filter(c => c.provCode === form_edit.user_detail.province)
})

const barangays = ref([])

async function fetchBarangays(citymunCode) {
    try {
        const res = await axios.get(`/api/barangays/${citymunCode}`)
        barangays.value = res.data
    } catch (err) {
        console.error('Failed to fetch barangays:', err)
        barangays.value = []
    }
}

onMounted(() => {
    if (form_edit.user_detail.citymun) {
        fetchBarangays(form_edit.user_detail.citymun)
    }
})

watch(() => form_edit.user_detail.citymun, (newCitymun) => {
    if (newCitymun) {
        fetchBarangays(newCitymun)
    } else {
        barangays.value = []
        form_edit.user_detail.barangay = ''
    }
})

function checkProvince() {
    if (!form_edit.user_detail.province) {
        toast.warning("Please select first Province", {
            icon: "fa fa-hand-paper",
        });
    }
}

function checkCitymun() {
    if (!form_edit.user_detail.citymun) {
        toast.warning("Please select first City / Municipality", {
            icon: "fa fa-hand-paper",
        });
    }
}

// Delete User
const confirmDelete = ref(false)
const form_delete = useForm({
    id: '',
    username: ''
})
const openDeleteModal = (user) => {
    form_delete.id = user.id
    form_delete.username = user.username
    confirmDelete.value = true
}

// Change/Reset Password
const changePassword = ref(false)
const form_change_pw = useForm({
    id: '',
    password: '',
    username: '',
    password_confirmation: '',
})
const openChangePwModal = (user) => {
    form_change_pw.id = user.id
    form_change_pw.username = user.username
    changePassword.value = true
}

// User Role
const updateRole = ref(false)
const form_update_role = useForm({
    id: '',
    roles: [],
    username: '',
})

const openUpdateRoleModal = (user) => {
    form_update_role.id = user.id
    form_update_role.username = user.username
    form_update_role.roles = user.user_detail?.roles ?? []
    updateRole.value = true
}

</script>
<template>

    <Head title="Libraries - Users"></Head>
    <MainLayout>
        <div>
            <div class="flex justify-between">
                <h1 class="text-3xl font-bold mb-4">Users Library</h1>
                <ButtonCom v-if="can.updateUserDetails" @click="addUser = true"
                    class="mb-4 border bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2">
                    <i class="fa fa-plus-circle"></i><span class="hidden md:inline  ml-4">Add New User</span>
                </ButtonCom>
            </div>
            <div class="mb-4">
                <form action="">
                    <Input type="text" id="searchText" placeholder="Search here..." v-model="searchInput"></Input>
                </form>
            </div>

            <!-- <FlashMessage></FlashMessage> -->
            <Table :columns="columns" :rows="props.users.data">
                <!-- TR -->
                <template v-slot:table_tr></template>
                <!-- TD -->
                <template v-slot:table_td="{ row, col }">
                    <template v-if="col.key === 'action'">
                        <div class="flex space-x-2">
                            <ButtonSmall class="bg-blue-500 hover:bg-blue-700 text-white" @click="openEditModal(row)">
                                <i class="fa fa-pencil-square"></i><span class="hidden md:inline">Edit</span>
                            </ButtonSmall>
                            <ButtonSmall class="bg-red-500 hover:bg-red-600 text-white" @click="openDeleteModal(row)">
                                <i class="fa fa-trash"></i><span class="hidden md:inline">Delete</span>
                            </ButtonSmall>
                            <ButtonSmall class="bg-orange-500 hover:bg-orange-600 text-white"
                                @click="openChangePwModal(row)">
                                <i class="fa fa-unlock"></i><span class="hidden md:inline">Change Password</span>
                            </ButtonSmall>
                            <ButtonSmall class="bg-blue-500 hover:bg-blue-700 text-white"
                                @click="openUpdateRoleModal(row)">
                                <i class="fa fa-user-plus"></i><span class="hidden md:inline">Update Role</span>
                            </ButtonSmall>
                        </div>
                    </template>
                    <template v-if="col.key === 'name'">
                        <strong class="uppercase">{{ row.last_name }}, {{ row.first_name }} {{ row.middle_name }}
                            {{ row.name_extension }}</strong>
                    </template>
                    <template v-else>
                        {{ row[col.key] }}
                    </template>
                </template>
            </Table>
            <Paginator :rows="props.users.links"></Paginator>
        </div>
    </MainLayout>

    <!-- Add User -->
    <Modal v-model="addUser">
        <template #modal_header>
            Add User
        </template>
        <template #modal_body>
            <form @submit.prevent="form.post('/libraries/users', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form.reset()
                        addUser = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }

                // for testing purposes only, but it works
                // onError: (errors) => {
                //     Object.values(errors).forEach(message => {
                //         toast.error(message, { icon: 'fas fa-exclamation-circle' })
                //     })
                // }
            })">
                <div class="px-4">
                    <div class="md:grid md:grid-cols-10 md:gap-4 space-y-3">
                        <div class="col-span-3">
                            <Input type="text" id="first_name" v-model="form.first_name" label="First Name"></Input>
                            <Error v-if="form.errors.first_name">{{ form.errors.first_name }}</Error>
                        </div>
                        <div class="col-span-3">
                            <Input type="text" id="middle_name" v-model="form.middle_name" label="Middle Name"></Input>
                            <Error v-if="form.errors.middle_name">{{ form.errors.middle_name }}</Error>
                        </div>
                        <div class="col-span-3">
                            <Input type="text" id="last_name" v-model="form.last_name" label="Last Name"></Input>
                            <Error v-if="form.errors.last_name">{{ form.errors.last_name }}</Error>
                        </div>
                        <div class="col-span-1">
                            <label class="w-25 text-sm font-bold text-gray-700 dark:text-white uppercase">
                                <span class="block md:hidden">Name Extension</span>
                                <span class="hidden md:inline">&nbsp;</span>
                            </label>
                            <input type="text" id="name_extension" v-model="form.name_extension"
                                class="w-full rounded-md border-2 border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-0 focus:border-cyan-700 focus:border-2"
                                title="Name Extension" />
                            <!-- <Input type="text" id="name_extension" v-model="form.name_extension" label="name extension"
                                title="Name Extension"></Input> -->
                            <Error v-if="form.errors.name_extension">{{ form.errors.name_extension }}</Error>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <Input type="email" id="email" v-model="form.email" label="Email"></Input>
                            <Error v-if="form.errors.email">{{ form.errors.email }}</Error>
                        </div>

                        <div>
                            <Input type="username" id="username" v-model="form.username" label="Username"></Input>
                            <Error v-if="form.errors.username">{{ form.errors.username }}</Error>
                        </div>

                        <div>
                            <Input type="password" id="password" v-model="form.password" label="Password"></Input>
                            <Error v-if="form.errors.password">{{ form.errors.password }}</Error>
                        </div>

                        <div>
                            <Input type="password" id="password_confirmation" v-model="form.password_confirmation"
                                label="Confirm Password"></Input>
                            <Error v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}
                            </Error>
                        </div>
                    </div>

                    <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                        <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Add User</ButtonCom>
                    </div>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Edit User -->
    <Modal v-model="editUser">
        <template #modal_header>
            Edit User
        </template>
        <template #modal_body>
            <form @submit.prevent="form_edit.put('/libraries/users/' + selectedUser.id, {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form_edit.reset()
                        editUser = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="px-4">
                    <div class="md:grid md:grid-cols-10 md:gap-4 space-y-3">
                        <div class="col-span-3">
                            <Input type="text" id="first_name" v-model="form_edit.first_name"
                                label="First Name"></Input>
                            <Error v-if="form_edit.errors.first_name">{{ form_edit.errors.first_name }}</Error>
                        </div>
                        <div class="col-span-3">
                            <Input type="text" id="middle_name" v-model="form_edit.middle_name"
                                label="Middle Name"></Input>
                            <Error v-if="form_edit.errors.middle_name">{{ form_edit.errors.middle_name }}</Error>
                        </div>
                        <div class="col-span-3">
                            <Input type="text" id="last_name" v-model="form_edit.last_name" label="Last Name"></Input>
                            <Error v-if="form_edit.errors.last_name">{{ form_edit.errors.last_name }}</Error>
                        </div>
                        <div class="col-span-1">
                            <label class="w-25 text-sm font-bold text-gray-700 dark:text-white uppercase">
                                <span class="block md:hidden">Name Extension</span>
                                <span class="hidden md:inline">&nbsp;</span>
                            </label>
                            <input type="text" id="name_extension" v-model="form_edit.name_extension"
                                class="w-full rounded-md border-2 border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-0 focus:border-cyan-700 focus:border-2"
                                title="Name Extension" />
                            <!-- <Input type="text" id="name_extension" v-model="form.name_extension" label="name extension"
                                title="Name Extension"></Input> -->
                            <Error v-if="form_edit.errors.name_extension">{{ form_edit.errors.name_extension }}</Error>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <Input type="email" id="email" v-model="form_edit.email" label="Email" readonly></Input>
                            <Error v-if="form_edit.errors.email">{{ form_edit.errors.email }}</Error>
                        </div>

                        <div>
                            <Input type="username" id="username" v-model="form_edit.username" label="Username"
                                readonly></Input>
                            <Error v-if="form_edit.errors.username">{{ form_edit.errors.username }}</Error>
                        </div>

                        <div>
                            <Input type="text" id="id" class="hidden" v-model="form_edit.id"></Input>
                        </div>
                    </div>

                    <div class="md:grid md:grid-cols-3 items-center md:gap-4 mb-4 sm:space-y-3 md:space-y-0">
                        <div>
                            <Input type="date" v-model="form_edit.user_detail.birthdate" label="Birthdate"
                                id="birthdate"></Input>
                            <Error v-if="form_edit.errors['user_detail.birthdate']">{{
                                form_edit.errors['user_detail.birthdate']
                            }}</Error>
                        </div>
                        <div>
                            <SelectInput v-model="form_edit.user_detail.gender" label="Gender" id="gender">
                                <template #options>
                                    <option value="0">Female</option>
                                    <option value="1">Male</option>
                                </template>
                            </SelectInput>
                            <Error v-if="form_edit.errors['user_detail.gender']">{{
                                form_edit.errors['user_detail.gender'] }}
                            </Error>
                        </div>
                        <div>
                            <Input type="text" v-model="form_edit.user_detail.contact" label="Contact"
                                id="contact"></Input>
                            <Error v-if="form_edit.errors['user_detail.contact']">{{
                                form_edit.errors['user_detail.contact'] }}
                            </Error>
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-11 items-center md:gap-4 mb-4">
                        <div class="col-span-1">
                            <Input type="text" v-model="form_edit.user_detail.house_number" label="Address"
                                id="house_number" placeholder="#"></Input>
                            <Error v-if="form_edit.errors['user_detail.house_number']">{{
                                form_edit.errors['user_detail.house_number'] }}
                            </Error>
                        </div>
                        <div class="col-span-3">
                            <Input type="text" v-model="form_edit.user_detail.street" id="street" label="&nbsp" class=""
                                placeholder="Street" title="Street"></Input>
                            <Error v-if="form_edit.errors['user_detail.street']">{{
                                form_edit.errors['user_detail.street']
                            }}
                            </Error>
                        </div>
                        <div class="col-span-2">
                            <SelectInput v-model="form_edit.user_detail.province" label="&nbsp" id="province"
                                title="Province">
                                <template #options>
                                    <option disabled value="">Province</option>
                                    <option v-for="province in props.provinces" :key="province.provCode"
                                        :value="province.provCode">
                                        {{ province.provDesc }}
                                    </option>
                                </template>
                            </SelectInput>
                            <Error v-if="form_edit.errors['user_detail.province']">{{
                                form_edit.errors['user_detail.province']
                            }}
                            </Error>
                        </div>
                        <div class="col-span-2">
                            <SelectInput v-model="form_edit.user_detail.citymun" label="&nbsp" id="citymun"
                                title="City / Municipality" @focus="checkProvince">
                                <template #options>
                                    <option disabled value="">City / Municipality</option>
                                    <option v-for="citymun in filteredCitymuns" :key="citymun.citymunCode"
                                        :value="citymun.citymunCode">
                                        {{ citymun.citymunDesc }}
                                    </option>
                                </template>
                            </SelectInput>
                            <Error v-if="form_edit.errors['user_detail.citymun']">{{
                                form_edit.errors['user_detail.citymun'] }}
                            </Error>
                        </div>
                        <div class="col-span-3">
                            <SelectInput v-model="form_edit.user_detail.barangay" label="&nbsp" id="barangay"
                                title="Barangay" @focus="checkCitymun">
                                <template #options>
                                    <option disabled value="">Barangay</option>
                                    <option v-for="barangay in barangays" :key="barangay.brgyCode"
                                        :value="barangay.brgyCode">
                                        {{ barangay.brgyDesc }}
                                    </option>
                                </template>
                            </SelectInput>
                            <Error v-if="form_edit.errors['user_detail.barangay']">{{
                                form_edit.errors['user_detail.barangay']
                            }}
                            </Error>
                        </div>
                    </div>
                    <div>
                        <div class="col-span-3">
                            <Input type="text" v-model="form_edit.user_detail.id_number" id="street" class=""
                                label="ID number" title="id_number"></Input>
                            <Error v-if="form_edit.errors['user_detail.id_number']">{{
                                form_edit.errors['user_detail.id_number']
                            }}
                            </Error>
                        </div>
                    </div>


                    <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                        <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Update User
                        </ButtonCom>
                    </div>
                </div>

            </form>
        </template>
    </Modal>

    <!-- Delete User -->
    <ModalConfirm v-model="confirmDelete">
        <template #modal_header>
            <div>
                <span class="text-red-500 text-sm"><i class="fa fa-exclamation-triangle"></i>
                    Are you sure you want to Delete the Data
                </span>
            </div>
        </template>

        <template #modal_body>
            <div class="mb-4">
                <span>Username: {{ form_delete.username }}</span>
            </div>
            <div class="flex items-center justify-end space-x-2">
                <form @submit.prevent="form_delete.delete('/libraries/users/' + form_delete.id, {
                    onSuccess: () => {
                        if ($page.props.flash.success) {
                            toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                            form_delete.reset()
                            confirmDelete = false
                        } else if ($page.props.flash.error) {
                            toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                        }
                    },

                    onError: () => {
                        toast.error('Something went wrong, please try again later', { icon: 'fas fa-exclamation-circle' })
                    }
                })">
                    <div>
                        <Input type="text" id="id" class="hidden" v-model="form_delete.id"></Input>
                    </div>
                    <ButtonCom type="submit"
                        class="bg-white text-red-500 px-4 py-2 border border-red-500 dark:border-red-500">Delete
                    </ButtonCom>
                </form>
                <ButtonCom type="submit" class="bg-blue-500 text-white px-4 py-2 border" @click="confirmDelete = false">
                    Cancel
                </ButtonCom>
            </div>
        </template>
    </ModalConfirm>

    <!-- Update/Change Password User -->
    <Modal v-model="changePassword">
        <template #modal_header>
            Edit User
        </template>
        <template #modal_body>

            <form @submit.prevent="form_change_pw.put('/libraries/users/' + form_change_pw.id + '/update_pw', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form_change_pw.reset()
                        changePassword = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="px-4">
                    <div class="space-y-3">
                        <div>
                            <span> Username: {{ form_change_pw.username }}</span>
                        </div>
                        <div>
                            <Input type="password" id="password" v-model="form_change_pw.password"
                                label="Password"></Input>
                            <Error v-if="form_change_pw.errors.password">{{ form_change_pw.errors.password }}</Error>
                        </div>

                        <div>
                            <Input type="password" id="password_confirmation"
                                v-model="form_change_pw.password_confirmation" label="Confirm Password"></Input>
                            <Error v-if="form_change_pw.errors.password_confirmation">{{
                                form_change_pw.errors.password_confirmation }}
                            </Error>
                        </div>

                        <div>
                            <Input type="text" id="id" class="hidden" v-model="form_change_pw.id"></Input>
                        </div>
                    </div>

                    <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                        <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Update Password
                        </ButtonCom>
                    </div>
                </div>
            </form>
        </template>
    </Modal>

    <!-- Update Role -->
    <Modal v-model="updateRole">
        <template #modal_header>
            Update Role
        </template>
        <template #modal_body>

            <form @submit.prevent="form_update_role.put('/libraries/users/' + form_update_role.id + '/update_roles', {
                onSuccess: () => {
                    if ($page.props.flash.success) {
                        toast.success($page.props.flash.success, { icon: 'fas fa-check-circle' })
                        form_update_role.reset()
                        updateRole = false
                    } else if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },

                onError: () => {
                    toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="px-4">
                    <div class="space-y-3 mb-4">
                        <span> Username: {{ form_update_role.username }}</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-h-60 overflow-y-auto border p-2 rounded-md">
                        <div v-for="role in roles" :key="role.id" class="p-2">
                            <InputCheckbox type="checkbox" :label="role.role_name" :id="`role-${role.id}`"
                                v-model="form_update_role.roles" :value="Number(role.id)">
                            </InputCheckbox>
                        </div>
                    </div>

                    <div class="pb-4 pt-8 justify-end flex space-x-4 items-center">
                        <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Update Roles
                        </ButtonCom>
                    </div>
                </div>
            </form>
        </template>
    </Modal>
</template>
