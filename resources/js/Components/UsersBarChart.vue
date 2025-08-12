<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3">Total Tasks: <b class="text-primary">{{ loaded ? tasksCount.tasks : 0 }}</b></div>
            <div class="col-md-3">Total Completed Tasks: <b class="text-success">{{ loaded ? tasksCount.tasksCompleted : 0 }}</b></div>
            <div class="col-md-3">Total Incomplete Tasks: <b class="text-danger">{{ loaded ? (tasksCount.tasks - tasksCount.tasksCompleted) : 0 }}</b></div>
            <div class="col-md-3">Total Unassigned Tasks: <b class="text-warning">{{ loaded ? tasksCount.tasksUnassigned : 0 }}</b></div>
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
