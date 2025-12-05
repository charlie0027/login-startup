<script setup>
import { useForm, usePage, Head } from '@inertiajs/vue3'
import Guest from '@/Components/Guest.vue'
import { useToast } from 'vue-toastification'
import Error from '../../Components/Error.vue'

const toast = useToast()

const page = usePage()
const form = useForm({
    token: page.props.token,
    email: page.props.email || '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/reset-password', {
        onSuccess: () => {
            toast.success('Password has been reset successfully', { icon: 'fas fa-check-circle' })
        },

        onError: () => {
            toast.error('Failed to reset password. Please check the errors.', { icon: 'fas fa-exclamation-circle' })
        },
    })
}
</script>

<template>

    <Head title="Reset Password"></Head>
    <Guest>
        <div class="container min-w-[500px] mx-auto p-6">
            <h1 class="text-xl font-semibold mb-4">Reset password</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <input type="hidden" v-model="form.token" />
                <div>

                    <Input label="Email" v-model="form.email" type="email" id="email" />
                    <Error v-if="form.errors.email">{{ form.errors.email }}</Error>
                </div>

                <div>
                    <Input label="New Password" v-model="form.password" type="password" id="password" />
                    <Error v-if="form.errors.password">{{ form.errors.password }}</Error>
                </div>

                <div>
                    <Input label="Confirm Password" v-model="form.password_confirmation" type="password"
                        id="password_confirmation" autocomplete="new-password" />
                </div>

                <div class="flex justify-end">
                    <ButtonCom type="submit" class="bg-blue-600 text-white px-4 py-2 rounded"
                        :disabled="form.processing">
                        Reset password
                    </ButtonCom>
                </div>


                <p v-if="$page.props.flash?.status" class="text-green-700 text-sm mt-2">
                    {{ $page.props.flash.status }}
                </p>
            </form>
        </div>
    </Guest>

</template>
