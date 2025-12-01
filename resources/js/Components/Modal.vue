<script setup>

import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
    modelValue: { type: Boolean, default: false }
})
const emit = defineEmits(['update:modelValue'])

function close() {
    emit('update:modelValue', false)
}

</script>

<template>
    <TransitionRoot as="template" :show="props.modelValue">
        <Dialog class="relative z-10" @close="close">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to=""
                leave="ease-in duration-200" leave-from="" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full justify-center p-4 text-center items-start">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to=" translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from=" translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all  dark:bg-gray-900 dark:text-white w-lg md:w-4xl lg:w-">
                            <div
                                class="flex justify-between items-center px-4 py-2 border-b bg-linear-to-r from-cyan-300 to-blue-500 dark:from-stone-700 dark:to-gray-400">
                                <DialogTitle as="h3" class="text-2xl font-bold uppercase">
                                    <slot name="modal_header"></slot>
                                </DialogTitle>
                                <button type="button"
                                    class="text-gray-600 dark:text-gray-200 focus:outline-none focus:none border-2 rounded-full px-2 flex items-center justify-center dark:hover:text-white hover:text-black cursor-pointer"
                                    @click="close">X</button>
                            </div>
                            <div class="p-4">
                                <slot name="modal_body"></slot>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
