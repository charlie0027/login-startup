<script setup>
import { Link } from '@inertiajs/vue3';
import Navbar from '../../Components/Navbar.vue';
import Sidebar from '../../Components/Sidebar.vue';

</script>
<template>
    <main>
        <header>
            <Navbar />
        </header>
        <article class="flex bg-linear-to-r dark:from-stone-700 dark:to-gray-400">
            <div class="py-2 px-1 md:p-4">
                <Sidebar />
            </div>
            <div
                class="dark:text-white m-2 md:m-4 p-4 shadow-lg border-8 border-double border-blue-900 dark:border-white rounded-lg dark:bg-gray-800 min-w-50 lg:w-full">
                <slot />
            </div>


        </article>
    </main>
</template>
