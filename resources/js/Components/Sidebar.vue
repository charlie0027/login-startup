<script setup>
import { Link, usePage } from '@inertiajs/vue3'
const page = usePage()

const can = page.props.can || {}
</script>

<style>
aside::-webkit-scrollbar {
    width: 12px;
}

aside::-webkit-scrollbar-track {
    background: #f0f0f0;
}

aside::-webkit-scrollbar-thumb {
    background-color: #1e40af;
    /* Tailwind's blue-900 */
    border-radius: 6px;
    border: 2px solid #f0f0f0;
}

/* Firefox support */
aside {
    scrollbar-width: thin;
    scrollbar-color: #1e2939 #f0f0f0;
}
</style>

<template>
    <aside
        class="w-16 md:w-64 shadow-lg border-8 border-double border-blue-900 dark:border-white rounded-lg dark:bg-gray-800 dark:text-white ">
        <nav
            class="md:p-4 space-y-2 overflow-y-scroll h-[calc(100vh-72px)] scrollbar scrollbar-thumb-blue-500 scrollbar-track-gray-200">
            <Link href="/dashboard" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                :class="{ 'font-bold underline underline-offset-4 text-blue-600 dark:text-blue-300': $page.url.startsWith('/dashboard') }"
                view-transition>
            <div class="flex items-center gap-2"> <i class="fas fa-bar-chart"></i> <span
                    class="hidden md:inline">Dashboard</span> </div>

            </Link>
            <div v-if="can.viewLibraries">
                <h2 class="text-2xl font-bold uppercase hidden md:inline"
                    :class="{ 'text-blue-600 dark:text-blue-300': $page.url.startsWith('/libraries') }">Libraries</h2>
                <Link href="/libraries/users" class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'font-bold underline underline-offset-4 text-blue-600 dark:text-blue-300': $page.url.startsWith('/libraries/users') }"
                    view-transition>
                <div class="flex items-center gap-2">
                    <i class="fas fa-users"></i><span class="hidden md:inline">Users</span>
                </div>

                </Link>
                <Link href="/libraries/user_roles"
                    class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'font-bold underline underline-offset-4 text-blue-600 dark:text-blue-300': $page.url.startsWith('/libraries/user_roles') }"
                    view-transition>
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-plus"></i><span class="hidden md:inline">User Roles</span>
                </div>

                </Link>

            </div>
        </nav>
    </aside>
</template>
