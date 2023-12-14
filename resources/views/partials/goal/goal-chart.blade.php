<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<script
    src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2/dist/chartjs-plugin-annotation.min.js"></script>
<div>
    <canvas  style="width: 50%; height: 30vh" id="myGoalChart"></canvas>
</div>
<script>
    var myGoalChartCtx = document.getElementById('myGoalChart').getContext('2d');
    var myGoalChartInstance = new Chart(myGoalChartCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($tableData as $dv => $value)
                    @if ($value->{$currentGoal['table_key']})
                    '{{ $value->{'date'} }}',
                @endif
                @endforeach
            ],
            datasets: [{
                label: '{{$currentGoal['goal_type_name']}}',
                data: [
                        @foreach($tableData as $dv => $value)
                        @if ($value->{$currentGoal['table_key']})

                    {
                        x: new moment('{{ $value->{'date'} }}'),
                        y: parseFloat({{ $value->{$currentGoal['table_key']} }}),
                    },
                    @endif
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
          //  tension: 0.2,
            scales: {
                y: {
                   // min: 1,
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
                    text: '{{$currentGoal['goal_name']}}'
                },
                tooltop: {
                    enabled: true,
                },
                autocolors: false,
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: {{intval($currentGoal['goal_value'])}},
                            yMax: {{intval($currentGoal['goal_value'])}},
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 2,
                        }
                    }
                }

            }
        }
    });

</script>
