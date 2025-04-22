<template>
    <div class="container">
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
            console.log(userlist.data.results.tasksPerUser);
            this.chartData = userlist.data.results.tasksPerUser;
            this.loaded = true;
        } catch (e) {
            console.error(e);
        }
    }
}
</script>
