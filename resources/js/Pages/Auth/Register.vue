<script setup>
import Guest from '@/Components/Guest.vue';
import ButtonCom from '../../Components/ButtonCom.vue';
import Input from '@/Components/Input.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import Error from '../../Components/Error.vue';


import { useToast } from "vue-toastification";

const toast = useToast();

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


</script>
<template>

    <Head title="Register"></Head>
    <Guest class="lg:w-[60%] md:w-[70%] sm:w-[80%]">
        <div class="text-3xl font-bold text-center pb-6 pt-4 uppercase">Registration Form</div>
        <div class="px-10">
            <form @submit.prevent="form.post('/register', {
                onError: () => {
                    toast.error('Please check form for corrections', { icon: 'fas fa-exclamation-circle' })
                }
            })">
                <div class="md:grid md:grid-cols-10 md:gap-4 space-y-2 ">
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
                            <span class="hidden md:block">&nbsp;</span>
                        </label>
                        <input type="text" id="name_extension" v-model="form.name_extension"
                            class="w-full rounded-md border-2 border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-0 focus:border-cyan-700 focus:border-2"
                            title="Name Extension" />
                        <!-- <Input type="text" id="name_extension" v-model="form.name_extension" label="&nbsp "
                            title="Name Extension"></Input> -->
                        <Error v-if="form.errors.name_extension">{{ form.errors.name_extension }}</Error>
                    </div>
                </div>
                <div class="space-y-2">
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
                        <Error v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</Error>
                    </div>
                </div>

                <div class="pb-10 pt-4 justify-end flex space-x-4 items-center">
                    <Link class="text-blue-500 underline" href="/">Back to Login</Link>
                    <ButtonCom type="submit" class="bg-blue-500 text-white w-[30%] px-4 py-2 ">Register</ButtonCom>
                </div>

            </form>

            <div>

            </div>
        </div>
    </Guest>
</template>
