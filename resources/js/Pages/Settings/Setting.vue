<script setup>
import MainLayout from '@/Pages/Layouts/MainLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { useToast } from "vue-toastification";

const page = usePage()
const toast = useToast()



const props = defineProps({
    require_email_verification: Boolean
})

const form = useForm({
    requireEmailVerification: props.require_email_verification,
})

function toggleVerification() {
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

</script>
<template>

    <Head title="Settings"></Head>
    <MainLayout>
        <div>

            <label>
                <input type="checkbox" v-model="form.requireEmailVerification" @change="toggleVerification">
                Require Email Verification
            </label>

        </div>
    </MainLayout>

</template>
