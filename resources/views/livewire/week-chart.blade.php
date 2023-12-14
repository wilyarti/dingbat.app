<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<div  class="">
    <canvas style="width: 50%; height: 30vh" id="myChart"></canvas>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                // i.e [2][20/8/20][0] => foo
                    @foreach ($dailySlotVolume[$selectedWeek->week_id] as $key => $value)
                        @if ($key)
                        @foreach ($value as $subKey => $subvalue)
                        '{{$subKey}}',
                @endforeach
                @endif
                @endforeach
            ],
            datasets: [
                    @foreach ($dailySlotVolume[$selectedWeek->week_id] as $key => $value)
                    @if ($key)
                        {
                            label: 'Slot {{chr(64 + $key + 1)}}',
                            borderWidth: 2,
                            backgroundColor: '{{$colorPalette[$key]}}',
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
