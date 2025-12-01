<script setup>
import MainLayout from '../Layouts/MainLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, watch } from 'vue';
import { useToast } from "vue-toastification";
import QrcodeVue from 'qrcode.vue';
import axios from 'axios';


const props = defineProps({
    users: Object,
    barangays: Array,
    citymuns: Array,
    provinces: Array,
    appUrl: String,
})

// toggle from view/edit mode
const isEditing = ref(false);
const page = usePage();

function toggleEdit() {
    if (isEditing.value) {
        form.put("/myprofile/" + props.users.id, {
            onSuccess: () => {
                if (page.props.flash.success) {
                    toast.success("Profile updated successfully!", { icon: 'fas fa-check-circle' });
                    isEditing.value = false;
                }
                else if (page.props.flash.error) {
                    toast.error(page.props.flash.error, { icon: 'fas fa-exclamation-circle' });
                    isEditing.value = true;
                }
            }
        })
    } else if (Object.keys(page.props.errors).length > 0) {
        toast.error("Please check form for corrections", { icon: 'fas fa-exclamation-circle' });
        isEditing.value = true;
    } else {
        isEditing.value = true;
    }
}

// Initialize form state with defaults
const form = useForm({
    first_name: props.users.first_name ?? '',
    middle_name: props.users.middle_name ?? '',
    last_name: props.users.last_name ?? '',
    username: props.users.username ?? '',
    email: props.users.email ?? '',
    user_detail: {
        birthdate: props.users.user_detail?.birthdate ?? '',
        gender: props.users.user_detail?.gender ?? '',
        contact: props.users.user_detail?.contact ?? '',
        house_number: props.users.user_detail?.house_number ?? '',
        street: props.users.user_detail?.street ?? '',
        barangay: props.users.user_detail?.barangay ?? '',
        citymun: props.users.user_detail?.citymun && props.citymuns.some(c => c.citymunCode === props.users.user_detail.citymun)
            ? props.users.user_detail.citymun
            : '',
        province: props.users.user_detail?.province &&
            props.provinces.some(p => p.provCode === props.users.user_detail.province)
            ? props.users.user_detail.province
            : '',
    }
})

// cancel edit
function resetForm() {
    form.reset()
    isEditing.value = false
}

const filteredCitymuns = computed(() => {
    if (!form.user_detail.province) return []
    return props.citymuns.filter(c => c.provCode === form.user_detail.province)
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
    if (form.user_detail.citymun) {
        fetchBarangays(form.user_detail.citymun)
    }
})

watch(() => form.user_detail.citymun, (newCitymun) => {
    if (newCitymun) {
        fetchBarangays(newCitymun)
    } else {
        barangays.value = []
        form.user_detail.barangay = ''
    }
})

const toast = useToast();

function checkProvince() {
    if (!form.user_detail.province) {
        toast.warning("Please select first Province", {
            icon: "fa fa-hand-paper",
        });
    }
}

function checkCitymun() {
    if (!form.user_detail.citymun) {
        toast.warning("Please select first City / Municipality", {
            icon: "fa fa-hand-paper",
        });
    }
}
</script>
<template>

    <Head title="My Profile"></Head>
    <MainLayout>
        <div class="">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold mb-2 md:mb-4 md:flex md:items-center space-x-2">
                    <span class="block">My Profile </span>
                    <ButtonSmall class="text-orange-500 hover:bg-gray-200 dark:hover:bg-blue-200" @click="toggleEdit">
                        <i :class="isEditing ? 'fa fa-save' : 'fa fa-edit'"></i>
                        <span class="ml-1 hidden md:inline">
                            {{ isEditing ? 'Save' : 'Edit' }}
                        </span>
                    </ButtonSmall>
                    <ButtonSmall v-if="isEditing" class="text-orange-500 hover:bg-gray-200 dark:hover:bg-blue-200"
                        @click="resetForm">
                        <span class="ml-1">
                            Cancel
                        </span>
                    </ButtonSmall>
                </h1>
                <!-- <QrcodeVue v-if="props.users?.user_detail?.qr_code" :value="props.users.user_detail.qr_code" :size="100"
                    class="mr-4 border rounded p-1" /> -->

                <QrcodeVue :value="props.appUrl + $page.url" :size="100" class="mr-4 border rounded p-1 mb-2 md:mb-0" />
            </div>
            <div class="">
                <div class="">
                    <div>
                        <h2 class="uppercase text-xl font-semibold">General Information</h2>
                    </div>
                    <div class="p-4">
                        <div class="md:grid md:grid-cols-3 items-center md:gap-4 mb-4 sm:space-y-3 md:space-y-0">
                            <Input type="text" v-model="form.first_name" label="First Name" id="first_name"
                                :disabled="!isEditing"></Input>
                            <Error v-if="form.errors.first_name">{{ form.errors.first_name }}</Error>
                            <Input type="text" v-model="form.middle_name" label="Middle Name" id="middle_name"
                                :disabled="!isEditing"></Input>
                            <Error v-if="form.errors.middle_name">{{ form.errors.middle_name }}</Error>
                            <Input type="text" v-model="form.last_name" label="Last Name" id="last_name"
                                :disabled="!isEditing"></Input>
                            <Error v-if="form.errors.last_name">{{ form.errors.last_name }}</Error>
                        </div>
                        <div class="md:grid md:grid-cols-2 items-center md:gap-4 mb-4 sm:space-y-3 md:space-y-0">
                            <Input type="text" v-model="form.username" label="Username" id="username"
                                :disabled="!isEditing"></Input>
                            <Error v-if="form.errors.username">{{ form.errors.username }}</Error>
                            <Input type="email" v-model="form.email" label="Email" id="email"
                                :disabled="!isEditing"></Input>
                            <Error v-if="form.errors.email">{{ form.errors.email }}</Error>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <h2 class="uppercase text-xl font-semibold">Other Details</h2>
                    </div>
                    <div class="p-4">
                        <div class="md:grid md:grid-cols-3 items-center md:gap-4 mb-4 sm:space-y-3 md:space-y-0">
                            <div>
                                <Input type="date" v-model="form.user_detail.birthdate" label="Birthdate" id="birthdate"
                                    :disabled="!isEditing"></Input>
                                <Error v-if="form.errors['user_detail.birthdate']">{{
                                    form.errors['user_detail.birthdate']
                                    }}</Error>
                            </div>
                            <div>
                                <SelectInput v-model="form.user_detail.gender" label="Gender" id="gender"
                                    :disabled="!isEditing">
                                    <template #options>
                                        <option value="0">Female</option>
                                        <option value="1">Male</option>
                                    </template>
                                </SelectInput>
                                <Error v-if="form.errors['user_detail.gender']">{{
                                    form.errors['user_detail.gender'] }}
                                </Error>
                            </div>
                            <div>
                                <Input type="text" v-model="form.user_detail.contact" label="Contact" id="contact"
                                    :disabled="!isEditing"></Input>
                                <Error v-if="form.errors['user_detail.contact']">{{
                                    form.errors['user_detail.contact'] }}
                                </Error>
                            </div>
                        </div>
                        <div class="md:grid md:grid-cols-11 items-center md:gap-4 mb-4">
                            <div class="col-span-1">
                                <Input type="text" v-model="form.user_detail.house_number" label="Address"
                                    id="house_number" placeholder="#" :disabled="!isEditing"></Input>
                                <Error v-if="form.errors['user_detail.house_number']">{{
                                    form.errors['user_detail.house_number'] }}
                                </Error>
                            </div>
                            <div class="col-span-3">
                                <Input type="text" v-model="form.user_detail.street" id="street" label="&nbsp" class=""
                                    placeholder="Street" title="Street" :disabled="!isEditing"></Input>
                                <Error v-if="form.errors['user_detail.street']">{{ form.errors['user_detail.street']
                                    }}
                                </Error>
                            </div>
                            <div class="col-span-2">
                                <SelectInput v-model="form.user_detail.province" label="&nbsp" id="province"
                                    title="Province" :disabled="!isEditing">
                                    <template #options>
                                        <option disabled value="">Province</option>
                                        <option v-for="province in props.provinces" :key="province.provCode"
                                            :value="province.provCode">
                                            {{ province.provDesc }}
                                        </option>
                                    </template>
                                </SelectInput>
                                <Error v-if="form.errors['user_detail.province']">{{
                                    form.errors['user_detail.province']
                                    }}
                                </Error>
                            </div>
                            <div class="col-span-2">
                                <SelectInput v-model="form.user_detail.citymun" label="&nbsp" id="citymun"
                                    title="City / Municipality" @focus="checkProvince" :disabled="!isEditing">
                                    <template #options>
                                        <option disabled value="">City / Municipality</option>
                                        <option v-for="citymun in filteredCitymuns" :key="citymun.citymunCode"
                                            :value="citymun.citymunCode">
                                            {{ citymun.citymunDesc }}
                                        </option>
                                    </template>
                                </SelectInput>
                                <Error v-if="form.errors['user_detail.citymun']">{{
                                    form.errors['user_detail.citymun'] }}
                                </Error>
                            </div>
                            <div class="col-span-3">
                                <SelectInput v-model="form.user_detail.barangay" label="&nbsp" id="barangay"
                                    title="Barangay" @focus="checkCitymun" :disabled="!isEditing">
                                    <template #options>
                                        <option disabled value="">Barangay</option>
                                        <option v-for="barangay in barangays" :key="barangay.brgyCode"
                                            :value="barangay.brgyCode">
                                            {{ barangay.brgyDesc }}
                                        </option>
                                    </template>
                                </SelectInput>
                                <Error v-if="form.errors['user_detail.barangay']">{{
                                    form.errors['user_detail.barangay']
                                    }}
                                </Error>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </MainLayout>

</template>
