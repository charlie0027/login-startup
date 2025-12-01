<script setup>
import { router, Head } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';

function logout() {
    router.post('/logout');
}

</script>
<template>

    <Head title="Dashboard"></Head>
    <MainLayout>
        <div class="min-w-500">
            <h1 class="text-3xl font-bold">Hello there, {{ $page.props.auth.first_name }}!</h1>

        </div>

    </MainLayout>
</template>
