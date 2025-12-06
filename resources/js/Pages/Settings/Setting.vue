<script setup>
import MainLayout from '@/Pages/Layouts/MainLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { useToast } from "vue-toastification";
import { ref } from 'vue';

const page = usePage()
const toast = useToast()

const can = page.props.can || {}

const verify_email_enabled = ref(false)
const props = defineProps({
    require_email_verification: Boolean,
    require_two_factor_auth: Boolean,
})

const form = useForm({
    requireEmailVerification: props.require_email_verification,
    requireTwoFactorAuth: props.require_two_factor_auth,
})

function toggleEmailVerification() {
    form.post('/settings/email-verification', {
        onSuccess: () => {
            if (page.props.flash.success) {
                toast.success(page.props.flash.success, { icon: 'fas fa-check-circle' })
            } else if (page.props.flash.error) {
                toast.error(page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
            }
        },

        onError: () => {
            toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
        }
    })
}

function toggleTwoFactorAuthentication() {
    form.post('/settings/two-factor-auth', {
        onSuccess: () => {
            if (page.props.flash.success) {
                toast.success(page.props.flash.success, { icon: 'fas fa-check-circle' })
            } else if (page.props.flash.error) {
                toast.error(page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
            }
        },

        onError: () => {
            toast.error('Please check the form for correction', { icon: 'fas fa-exclamation-circle' })
        }
    })
}

</script>
<template>

    <Head title="Settings"></Head>
    <MainLayout>
        <div class=" border rounded p-4">
            <div class="flex items-center mb-4">
                <h1 class="text-2xl uppercase mr-2">Security Settings</h1>
                <Tooltip>
                    <p>Email Verification Enabled:</p>
                    <p class="mt-1">An email will be sent if the user’s email is not yet verified.</p>
                    <p class="mt-2">Two Factor Authentication Enabled:</p>
                    <p class="mt-1">A random code will be sent via email to proceed with login.</p>
                </Tooltip>
            </div>

            <div class="grid grid-cols-2">
                <div class="sm:col-span-2 md:col-span-1">
                    <div class="flex items-center">
                        <!-- Email Verification Toggle -->
                        <InputToggle v-model="form.requireEmailVerification" label="Require Email Verification"
                            enabledText="Enabled" disabledText="Disabled" color="green"
                            @update:modelValue="toggleEmailVerification" :disabled="!can.updateSettings" />
                    </div>
                </div>

                <div class="sm:col-span-2 md:col-span-1">
                    <!-- Two Factor Authentication Toggle -->
                    <div class="flex">
                        <InputToggle v-model="form.requireTwoFactorAuth" label="Require Two Factor Authentication"
                            enabledText="Enabled" disabledText="Disabled" color="blue"
                            @update:modelValue="toggleTwoFactorAuthentication" :disabled="!can.updateSettings" />
                    </div>


                </div>
            </div>
        </div>


    </MainLayout>

</template>
