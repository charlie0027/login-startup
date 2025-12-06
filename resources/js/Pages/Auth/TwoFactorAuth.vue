<script setup>
import Guest from '@/Pages/Layouts/Guest.vue'
import { useForm, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast()
const form = useForm({
    code: '',
})

function logout() {
    router.post('/logout')
}
</script>
<template>
    <Guest>
        <div class="container p-4">
            <h1 class="text-2xl font-bold mb-4 text-center uppercase">two factor authentication</h1>
            <p class="">Please enter the code we've sent to your registered email</p>
            <form @submit.prevent="form.post('/two-factor', {
                onSuccess: () => {
                    if ($page.props.flash.error) {
                        toast.error($page.props.flash.error, { icon: 'fas fa-exclamation-circle' })
                    }
                },
                onError: (errors) => {
                    toast.error(errors.code, { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="py-6">
                    <Input type="text" v-model="form.code" id="code" label="Code"></Input>
                </div>
                <div class="flex gap-2 justify-end">
                    <ButtonCom type="submit" class="px-3 py-1 bg-gray-500 hover:bg-gray-700 text-white">Verify
                    </ButtonCom>
                    <ButtonCom class="px-3 py-1 hover:bg-gray-300" @click="logout">Logout</ButtonCom>
                </div>

            </form>
        </div>

    </Guest>

</template>
