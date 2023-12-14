<div>
    <div>
        <div class="text-center text-2xl">
            Exercise History for {{$start}} - {{$end}}
            <br/>
        </div>
        <br/>
    </div>

    @include('partials.exercises.date-selector')
    <br/>

    <div class="flex justify-between max-w-lg ">
        @include('partials.chart', ['colorPalette' => $volumeColorPalette])
        @include('partials.muscle-selector', ['$totalReps'=>$totalReps, '$volume'=>$volume, 'exercisesMuscles'=>$exercisesMuscles , 'colorPalette'=> $volumeColorPalette])
    </div>
    @include('partials.exercises.exercises-chart', ['colorPalette' => $exercisesColorPalette])
    @include('partials.chart-dailyvolume', ['colorPalette' => $volumeColorPalette])
    @include('partials.exercises.workout-log')

    @if (session()->has('message'))
        <br/>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            {{ session('message') }}
        </div>
    @endif
    <script>
        window.addEventListener('dateRange', event => {
            window.location.href = '/exercises/view/' + event.detail.start + ":" + event.detail.end;
        })
    </script>
</div>
