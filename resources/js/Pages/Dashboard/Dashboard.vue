<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import Chart from '@/Components/Chart.vue';
import moment from 'moment';
import ct from "countries-and-timezones";
import { ref, onMounted, onUnmounted, computed, Text } from 'vue'

const props = defineProps({
    gender_total: Array,
    role_total: Array
});

// reactive theme state
const theme = ref(document.documentElement.getAttribute('data-theme') || 'light')

// watch for changes in DOM attribute
const observer = new MutationObserver(() => {
    theme.value = document.documentElement.getAttribute('data-theme')
})
observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] })

const textColor = computed(() => theme.value === 'dark' ? '#e0e0e0' : '#222')

// Time Zone and Country Detection
const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
const country = ct.getCountryForTimezone(tz);
const time_now = ref(moment().format('LTS'));
const date_now = moment().format('LL');
const today = moment().format('ddd');

let timer = null

const genderChartRef = ref(null)

onMounted(() => {
    timer = setInterval(() => {
        time_now.value = moment().format('LTS')
    }, 1000)

})

onUnmounted(() => {
    clearInterval(timer)
})


// Pie Chart Data Preparation
const labels = props.gender_total.map(item => item.gender_label);
const data = props.gender_total.map(item => item.total);
const grandTotal = data.reduce((sum, val) => sum + val, 0);

// Bar Chart Data Preparation
const barLabels = props.role_total.map(item => item.role_label);
const barData = props.role_total.map(item => item.total);
const barGrandTotal = barData.reduce((sum, val) => sum + val, 0);


</script>
<template>

    <Head title="Dashboard"></Head>
    <MainLayout>
        <div class="mb-4 md:flex items-center justify-between">
            <h1 class="text-4xl font-bold mb-4">Oh, Hi {{ $page.props.auth.first_name }}!</h1>
            <div class="border-2 rounded-xl py-1 px-2 bg-gray-200 dark:bg-gray-500 w-52 md:justify-end">
                <div class="">
                    <span class="font-bold text-2xl">{{ country.id }}&nbsp<span>{{ time_now }}</span></span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ date_now }} <span>({{ today
                            }})</span></span>
                </div>

            </div>
        </div>
        <div class="md:grid grid-cols-2 gap-4">
            <!-- Pie Chart -->
            <div class="mb-4 md:mb-0 border-2 rounded-lg sm:col-span-2 md:col-span-1 ">
                <Chart :chart-data="{

                    labels: labels,
                    datasets: [{
                        // label: 'Gender Distribution',
                        data: data,
                        backgroundColor: [
                            'rgb(255, 99, 132)',   // red
                            'rgb(54, 162, 235)',   // blue
                            'rgb(255, 205, 86)',   // yellow
                            'rgb(75, 192, 192)',   // teal
                            'rgb(153, 102, 255)',  // purple
                            'rgb(255, 159, 64)'    // orange
                        ],
                        hoverOffset: 4,
                    }]
                }" :chart-options="{
                    plugins: {
                        legend: {
                            position: 'right',   // moves legend to the right
                            align: 'end',         // pushes it to the bottom (lower right portrait)
                            labels: {
                                color: textColor
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value, ctx) => {
                                // Show both label and total
                                const label = ctx.chart.data.labels[ctx.dataIndex];
                                // return `${label}: ${value}`;
                                return `${value}`;
                            },
                        },
                        title: {
                            display: true,
                            text: `Gender Distribution (Total: ${grandTotal})`,
                            font: {
                                size: 18
                            },
                            color: textColor
                        },

                    },
                }" class="min-w-full px-4 pb-10" ref="genderChartRef" />
            </div>

            <!-- Bar Chart -->
            <div class="border-2 rounded-lg sm:col-span-2 md:col-span-1">
                <Chart type="bar" :chart-data="{
                    labels: barLabels,
                    datasets: [{
                        label: 'Number of Users',
                        data: barData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }],
                }" :chart-options="{
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: Math.max(...barData) + 5,
                            ticks: {
                                stepSize: 5,
                                color: textColor
                            },
                            grid: {
                                color: textColor,
                                borderColor: textColor
                            }
                        },
                        x: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: textColor,
                                borderColor: textColor
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'User Roles Distribution (Total: ' + barGrandTotal + ')',
                            font: { size: 18 },
                            color: textColor
                        }, datalabels: {
                            color: textColor,
                            font: { weight: 'bold' },
                            anchor: 'end',
                            align: 'top',
                            formatter: (value) => value
                        }, legend: {
                            labels: {
                                color: textColor
                            }
                        },
                    }
                }" class="min-w-full md:min-h-[350px]" />
            </div>

        </div>

    </MainLayout>
</template>
