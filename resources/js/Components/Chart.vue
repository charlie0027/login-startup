<!-- This is for all Charts -->

<script setup>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    RadialLinearScale
} from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import { Pie, Bar, Line, Doughnut, Radar, PolarArea } from 'vue-chartjs';

// Register all chart elements once globally
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    ArcElement,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    RadialLinearScale,
    ChartDataLabels
);

const props = defineProps({
    type: {
        type: String,
        default: 'pie' // options: 'pie', 'bar', 'line', 'doughnut', 'radar', 'polarArea'
    },
    chartData: {
        type: Object,
        required: true
    },
    chartOptions: {
        type: Object,
        default: () => ({})
    },
    // id: {
    //     type: String,
    //     default: 'universal-chart'
    // },
    class: {
        type: String,
        default: ''
    }
});

// Map chart types to vue-chartjs components
const chartMap = {
    pie: Pie,
    bar: Bar,
    line: Line,
    doughnut: Doughnut,
    radar: Radar,
    polarArea: PolarArea
};

const ChartComponent = chartMap[props.type] || Pie;
</script>

<template>
    <div class="w-full max-w-full overflow-x-auto p-4">
        <ChartComponent v-bind="$attrs" :data="chartData" :options="{
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: { size: 12 }
                    }
                },
                title: {
                    display: true,
                    text: chartOptions?.title?.text || 'Chart Overview',
                    font: { size: 16 }
                },
                datalabels: {
                    color: '#000',
                    font: { weight: 'bold' },
                    formatter: (value, ctx) => {
                        const label = ctx.chart.data.labels[ctx.dataIndex];
                        return props.type === 'pie' ? `${label}: ${value}` : value;
                    }
                }
            },
            ...chartOptions
        }" :class="class" />
    </div>
</template>
