<script setup>

import Input from '@/Components/Input.vue';
import ButtonCom from '@/Components/ButtonCom.vue';
import { ref } from 'vue';
import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Error from '../Components/Error.vue';

import { useToast } from "vue-toastification";

const toast = useToast();

const form = useForm({
    username: '',
    password: '',
});

const page = usePage();
const message = page.props.flash?.message
</script>
<template>

    <Head title="HRMIS"></Head>
    <Card class="lg:w-[30%] md:w-[50%] sm:w-[70%]">
        <div class="grid grid-cols-3 justify-center items-center pb-4">
            <div class="col-span-2">
                <h2 class="font-bold text-7xl text-center">HRMIS</h2>
            </div>
            <div class="w-32 h-32">
                <img src="../../../public/img/sample_logo.png" alt="">
            </div>
        </div>

        <div class="px-10">
            <div v-if="message" class="text-red-500 mb-4">
                {{ message }}
            </div>
            <form @submit.prevent="form.post('/login', {
                onError: () => {
                    toast.error('Please check your credentials', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="pb-2" v-if="form.errors.username || form.errors.password">
                    <Error>The provided credentials do not match our records.</Error>
                </div>
                <Input label="Username" id="username" type="text" v-model="form.username" class="mb-4"></Input>

                <Input type="password" label="Password" id="password" v-model="form.password" class="mb-4"></Input>
                <div class="pb-10 pt-4 justify-end flex space-x-4 items-center">
                    <Link class="text-blue-500 underline" href="/register">Click here to register</Link>
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2">Login</ButtonCom>
                </div>
            </form>
        </div>
    </Card>
</template>
