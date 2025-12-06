<script setup>
import { router, usePage, Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Guest from '@/Pages/Layouts/Guest.vue'

const page = usePage()
const sending = ref(false)
const email = computed(() => page.props.auth?.user?.email ?? '')
const flashMessage = computed(() => page.props.flash?.message ?? '')

function resend() {
    sending.value = true
    router.post('/email/verification-notification', {}, {
        onFinish: () => { sending.value = false }
    })
}

function logout() {
    router.post('/logout')
}
</script>

<template>

    <Head title="Verify Email"></Head>
    <Guest>
        <div class="container p-4">
            <h1 class="text-2xl font-bold mb-4 text-center uppercase">Verify your email</h1>
            <p class="mb-4">We’ve sent a verification link to email. Please click it to continue.</p>

            <div class="flex gap-2 justify-end">
                <ButtonCom class="px-3 py-1 bg-gray-500 hover:bg-gray-700 text-white" @click="resend"
                    :disabled="sending">
                    {{ sending ? 'Sending...' : 'Resend verification email' }}
                </ButtonCom>
                <ButtonCom class="px-3 py-1 hover:bg-gray-300" @click="logout">Logout</ButtonCom>
            </div>

            <p v-if="flashMessage" class="mt-3 text-success">{{ flashMessage }}</p>
        </div>
    </Guest>

</template>
