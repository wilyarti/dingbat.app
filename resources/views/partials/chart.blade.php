<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<div class="w-6/12">
    <canvas id="myPieChartMuscles"></canvas>
</div>
<script>
    var myPieChartMusclesCtx = document.getElementById('myPieChartMuscles').getContext('2d');
    myPieChartMusclesCtx.height = 50;
    var myPieChartMusclesInstance = new Chart(myPieChartMusclesCtx, {
        type: 'pie',
        data: {
            labels: [
                @foreach($volume as $key => $value)
                    '{{$key}} {{intval((100/$totalReps)*$value)}}%',
                //' {{$key}} {{$value}}',
                @endforeach
            ],
            datasets: [{
                label: 'Muscle Group Percentage',
                data: [
                    @foreach($volume as $key => $value)
                        '{{$value}}',
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($colorPalette as $key)
                        '{{$key}}',
                    @endforeach
                ],
                borderColor: [
                    @foreach($colorPalette as $key)
                        '{{$key}}',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    display: false,
                    beginAtZero: true,
                    gridLines: false,
                },
                x: {
                    display: false,
                    beginAtZero: true,
                    gridLines: false,
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '{{$chartTitle}}'
                },
                tooltop: {
                    enabled: false,
                }

            }
        }
    });
</script>
