<script>
    import { Doughnut } from 'vue-chartjs'

    export default {
        extends: Doughnut,
        props: {
            chartdata: {
                type: Object,
                default: null
            },
        },
        data: () => ({
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    intersect: false,
                    callbacks: {
                        label: function (context, data) {
                            let label = data.labels[context.index] || '';

                            if (label) {
                                label += ': ';
                            }
                            if (context.value !== null) {
                                label += new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(data.datasets[context.datasetIndex].data[context.index]);
                            }

                            return label;
                        }
                    }
                },
                legend: {
                    position: 'right'
                }

            }
        }),

        mounted () {
            this.renderChart(this.chartdata, this.options)
        }
    }
</script>

