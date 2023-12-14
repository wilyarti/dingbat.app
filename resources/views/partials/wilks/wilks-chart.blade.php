<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<div>
    <canvas style="width: 50%; height: 30vh" id="myWilksChart"></canvas>
</div>
<script>
    var myWilksChartCtx = document.getElementById('myWilksChart').getContext('2d');
    var myWilksChartInstance = new Chart(myWilksChartCtx, {
        type: 'line',
        data: {
            labels: [
                "Weight"
            ],
            datasets: [{
                label: 'Wilks Curve',
                data: [
                    @foreach($wilks as $dv => $value)
                        {
                            x: parseInt({{$dv}}),
                            y: parseFloat({{$value}})
                        },
                    @endforeach
                ],
                borderWidth: 2,
                borderColor: '#e76f51',
                spanGaps: true,
            },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            tension: 0.2,
            scales: {
                y: {
                    //min: 1,

                },
                x: {
                    type: 'linear',
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 20
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    display: true
                },
                title: {
                    display: true,
                    text: 'Slot Volume'
                },
                tooltop: {
                    enabled: true,
                }

            }
        }
    });
</script>
