<script setup>
import { toast } from 'vue3-toastify';
</script>

<template>
    <div v-if="$page.props.flash.success" class="bg-green-500 text-white p-2">
        {{ $page.props.flash.success }}
    </div>
</template>
