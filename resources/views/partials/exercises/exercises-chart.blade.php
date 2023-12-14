<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<div class="">
    <canvas style="width: 50%; height: 30vh" id="myExercisesDailyVolumeChart"></canvas>
</div>
<script>
    var myExercisesDailyVolumeChartCtx = document.getElementById('myExercisesDailyVolumeChart').getContext('2d');
    var myExercisesDailyVolumeChartInstance = new Chart(myExercisesDailyVolumeChartCtx, {
        type: 'bar',
        data: {
            labels: [
                // i.e [2][20/8/20][0] => foo
                @foreach ($dailyMuscleVolume as $key => $value)
                    @if ($key)
                    @foreach ($value as $subKey => $subvalue)
                    '{{$subKey}}',
                @endforeach
                @endif
                @endforeach
            ],
            datasets: [
                    @foreach ($dailyMuscleVolume as $key => $value)
                    @if ($key)
                {
                    label: '{{$key}}',
                    borderWidth: 2,
                    borderColor: '{{$colorPalette[$loop->index]}}',
                    data: [
                            @foreach ($value as $subKey => $subvalue)
                        {
                            x: new moment('{{$subKey}}'),
                            y: parseInt({{$subvalue}}),
                        },

                        @endforeach
                    ],

                },
                @endif
                @endforeach
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
                    text: 'Volume by Muscle Group'
                },
                tooltop: {
                    enabled: true,
                }

            }
        }
    });
</script>
