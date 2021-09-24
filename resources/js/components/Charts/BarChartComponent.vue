<script>
    import { Bar } from 'vue-chartjs'

    export default {
        extends: Bar,
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
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
                            }
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    intersect: false,
                    callbacks: {
                        label: function (context) {
                            let label = context.label || '';

                            if (label) {
                                label += ': ';
                            }
                            if (context.value !== null) {
                                label += new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(context.value);
                            }

                            return label;
                        }
                    }
                },
            }
        }),

        mounted () {
            this.renderChart(this.chartdata, this.options)
        }
    }
</script>
