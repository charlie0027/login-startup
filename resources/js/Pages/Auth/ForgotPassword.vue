<script setup>
import Guest from '@/Components/Guest.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'

import { useToast } from "vue-toastification";

const toast = useToast();

const form = useForm({
    email: '',
})

const submit = () => {
    form.post('/forgot-password', {
        onSuccess: () => {
            toast.success('Password reset link sent to your email', { icon: 'fas fa-check-circle' })
        },
    })
}
</script>

<template>

    <Head title="Forgot Password"></Head>
    <Guest>
        <div class="mx-auto p-6 min-w-[400px] max-w-md">
            <div class="flex justify-center uppercase">
                <h1 class="text-2xl font-bold mb-4">Forgot password</h1>
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <Input label="Email" id="email" type="email" v-model="form.email"></Input>
                    <div class="" v-if="form.errors.email">
                        <Error>The provided credentials do not match our records.</Error>
                    </div>
                </div>

                <div class="pb-10 pt-4 justify-center flex space-x-4 items-center">
                    <ButtonCom type="submit" class="bg-blue-500 text-white px-4 py-2" :disabled="form.processing">Send
                        Reset Password Link</ButtonCom>
                </div>
                <p v-if="$page.props.flash?.status" class="text-green-700 text-sm mt-2">
                    {{ $page.props.flash.status }}
                </p>

                <div class="flex justify-center">
                    <Link class="text-blue-500 underline" href="/login">Back to login</Link>
                </div>
            </form>


        </div>
    </Guest>

</template>
