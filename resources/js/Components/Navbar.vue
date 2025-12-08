<script setup>
import { useTheme } from '@/MyJs/useTheme.js';
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const { isDark, toggleTheme } = useTheme()

const showDropdown = ref(false);

function goToProfile() {
    const userId = usePage().props.auth.id
    router.visit('/myprofile/' + userId)
}
</script>


<template>
    <header
        class="bg-linear-to-r from-cyan-300 to-blue-500 transition-all dark:from-gray-800 dark:to-gray-800 dark:text-white  shadow px-4 py-3 flex justify-between items-center border-b-2 border-blue-900 dark:border-white min-w-xs md:max-w-full m-0">
        <h1 class="text-lg font-semibold dark:text-white">My App</h1>
        <div class="flex space-x-4 items-center">
            <button @click="toggleTheme" class="text-yellow-500  cursor-pointer text-2xl font-extrabold">
                {{ isDark ? '☼' : '☾' }} </button>
            <!-- Dropdown Trigger -->
            <div class="relative" @click="showDropdown = !showDropdown">
                <button
                    class="text-sm dark:text-white bg-white border-2 dark:bg-gray-700 border-blue-900 dark:border-white rounded-2xl shadow hover:bg-gray-100 dark:hover:bg-gray-600 py-1 px-5 cursor-pointer">
                    {{ $page.props.auth.last_name }}, {{ $page.props.auth.first_name[0].toUpperCase()
                    }}
                    <span v-if="showDropdown">▴</span>
                    <span v-else>▾</span>
                </button>

                <!-- Dropdown Menu -->
                <div v-if="showDropdown"
                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded shadow-lg z-10">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                        <li>
                            <button @click="goToProfile"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                My Profile
                            </button>
                        </li>
                        <li>
                            <Link method="post" href="/logout"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                Logout
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</template>
