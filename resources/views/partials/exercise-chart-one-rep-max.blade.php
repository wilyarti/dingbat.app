<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<div  class="">
    <canvas style="width: 50%; height: 30vh"id="myChart"></canvas>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($sets as $set)
                '{{date_format(new DateTime($set->date, Auth::user()->time_zone), 'd M y')}}',
                @endforeach
            ],
            datasets: [{
                label: 'Weight (kgs)',
                data: [
                    @foreach($sets as $set)
                        {{$set->weight}},
                    @endforeach
                ],
                borderWidth: 2,
                borderColor: '#e76f51',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    min: 1,

                },
                x: {
                 //   type: 'timeseries',
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 20
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '{{$exercise_name}}'
                },
                tooltop: {
                    enabled: true,
                }

            }
        }
    });
</script>
