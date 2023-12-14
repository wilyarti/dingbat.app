<div class=" ">
    @include('partials.exercise-chart-one-rep-max', ['sets' => $sets, 'exercise_name' => $exercise_name])
    @include('partials.exercise-chart-volume-per-set', ['exercise_name' => $exercise_name,'workOutSlots' => $workOutSlots])

    @include('partials.exercise-history',  ['oneRepMaxDB' => $oneRepMaxDB ])
</div>
