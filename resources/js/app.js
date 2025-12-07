import {
    createApp,
    h
} from 'vue'
import {
    createInertiaApp
} from '@inertiajs/vue3'

import '@tailwindplus/elements';
import '@headlessui/vue';
// import Toast from "vue-toastification";
import Toast from "vue-toastification";

import "vue-toastification/dist/index.css";

import Table from '@/Components/Table.vue'
import ButtonCom from '@/Components/ButtonCom.vue'
import Modal from '@/Components/Modal.vue'
import Input from '@/Components/Input.vue'
import Error from '@/Components/Error.vue'
import Paginator from '@/Components/Paginator.vue'
import ModalConfirm from '@/Components/ModalConfirm.vue'
import ButtonSmall from '@/Components/ButtonSmall.vue'
import SelectInput from '@/Components/SelectInput.vue'
import InputCheckbox from '@/Components/InputCheckbox.vue'
import InputTextarea from '@/Components/InputTextarea.vue'
import InputToggle from './Components/InputToggle.vue';
import Tooltip from './Components/Tooltip.vue';
import Tabs from './Components/Tabs.vue';

const options = {
    // You can set your default options here
};

createInertiaApp({
    resolve: name => {
        const pages =
            import.meta.glob('./Pages/**/*.vue', {
                eager: true
            })
        return pages[`./Pages/${name}.vue`]
    },
    setup({
        el,
        App,
        props,
        plugin,
    }) {
        const app = createApp({
            render: () => h(App, props)
        })
        app.use(plugin)
        app.use(Toast, options)
        app.component('Table', Table)
        app.component('ButtonCom', ButtonCom)
        app.component('Modal', Modal)
        app.component('Input', Input)
        app.component('Error', Error)
        app.component('Paginator', Paginator)
        app.component('ModalConfirm', ModalConfirm)
        app.component('ButtonSmall', ButtonSmall)
        app.component('SelectInput', SelectInput)
        app.component('InputCheckbox', InputCheckbox)
        app.component('InputTextarea', InputTextarea)
        app.component('InputToggle', InputToggle)
        app.component('Tooltip', Tooltip)
        app.component('Tabs', Tabs)
        app.mount(el)
    },
    progress: {
        color: '#00008B',
    }
})
