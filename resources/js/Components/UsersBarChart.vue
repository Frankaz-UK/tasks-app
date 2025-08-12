<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">Total Tasks: {{ tasksCount.tasks }}</div>
            <div class="col-md-6">Total Completed Tasks: {{ tasksCount.tasksCompleted }}</div>
        </div>
        <Bar v-if="loaded" :data="chartData" :options="chartOptions" />
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    name: 'UsersBarChart',
    components: { Bar },
    data: () => ({
        loaded: false,
        chartData: null,
        tasksCount: null,
        chartOptions: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    }),
    async mounted () {
        this.loaded = false;

        try {
            const userlist = await axios.get(route('dashboard.api.index'));
            this.chartData = userlist.data.results.tasksPerUser;
            this.tasksCount = userlist.data.results.tasksCount;
            this.loaded = true;
        } catch (e) {
            console.error(e);
        }
    }
}
</script>
