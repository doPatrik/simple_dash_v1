<template>
    <div class="chart-container">
        <div class="bar-chart-container">
            <h2>Számlák {{currentyear}}</h2>
            <hr>
            <spinner-component :is-loaded="!loaded.barChart"></spinner-component>
            <bar-chart-component :chartdata="barChartData" v-if="loaded.barChart" :styles="{height: '400px', width: '400px', position: 'relative'}"></bar-chart-component>
        </div>
        <div class="line-chart-container">
            <h2>Kiadások {{currentyear}}</h2>
            <hr>
            <spinner-component :is-loaded="!loaded.lineChart"></spinner-component>
            <line-chart-component :chartdata="lineChartData" v-if="loaded.lineChart" :styles="{height: '400px', width: '400px', position: 'relative'}"></line-chart-component>
        </div>
        <div class="line-chart-container">
            <h2>Éves statisztika {{currentyear}}</h2>
            <hr>
            <spinner-component :is-loaded="!loaded.doughnutChart"></spinner-component>
            <doughnut-chart-component :chartdata="doughnutChartData" v-if="loaded.doughnutChart" :styles="{height: '400px', width: '400px', position: 'relative'}"></doughnut-chart-component>
        </div>
    </div>
</template>

<script>
    import BarChartComponent from "../Charts/BarChartComponent";
    import LineChartComponent from "../Charts/LineChartComponent";
    import DoughnutChartComponent from "../Charts/DoughnutChartComponent";
    import SpinnerComponent from "../Loading/SpinnerComponent";

    export default {
        components: {
            BarChartComponent,
            LineChartComponent,
            DoughnutChartComponent,
            SpinnerComponent,
        },
        props: ['currentyear'],
        data() {
            return {
                loaded: {
                    barChart: false,
                    lineChart: false,
                    doughnutChart: false,
                },
                barChartData: {
                    labels: [],
                    datasets: [
                        {
                            label: '',
                            backgroundColor: '',
                            data: []
                        }
                    ]
                },
                lineChartData: {
                    labels: [],
                    datasets: [
                        {
                            label: '',
                            backgroundColor: '',
                            data: []
                        }
                    ]
                },
                doughnutChartData: {
                    labels: [],
                    datasets: [
                        {
                            label: '',
                            backgroundColor: '',
                            data: []
                        }
                    ]
                },
            }
        },
        methods: {
            getBarChartData() {
                axios.get('/home/getBarChartData').then(res => {
                    if (res.status == 200) {
                        this.barChartData = res.data;
                        this.loaded.barChart = true;
                    }
                })
            },
            getLineChartData() {
                axios.get('/home/getLineChartData').then(res => {
                    if (res.status == 200) {
                        this.lineChartData = res.data;
                        this.loaded.lineChart = true;
                    }
                })
            },
            getDoughnutChartData() {
                axios.get('/home/getDoughnutChartData').then(res => {
                    if (res.status == 200) {
                        this.doughnutChartData = res.data;
                        this.loaded.doughnutChart = true;
                    }
                })
            }
        },
        mounted() {
            this.loaded.barChart = false;
            this.getBarChartData();
            this.loaded.lineChart = false;
            this.getLineChartData();
            this.getDoughnutChartData();
        }
    }
</script>
