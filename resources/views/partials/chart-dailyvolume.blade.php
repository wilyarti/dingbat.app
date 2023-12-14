<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<div>
    <canvas style="width: 50%; height: 30vh" id="myDailyVolumeChart"></canvas>
</div>
<script>
    var myDailyVolumeChartCtx = document.getElementById('myDailyVolumeChart').getContext('2d');
    var myDailyVolumeChartInstance = new Chart(myDailyVolumeChartCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dailyVolume as $dv => $value)
                    '{{$dv}}',
                @endforeach
            ],
            datasets: [{
                label: 'Daily Volume',
                data: [
                        @foreach($dailyVolume as $dv => $value)
                    {
                        x: new moment('{{$dv}}'),
                        y: parseInt({{$value}}),
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
                    min: 1,

                },
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    },
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
