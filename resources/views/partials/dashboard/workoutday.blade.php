<div>
    @include('partials.dashboard.title')
    <!-- Charts -->
    @if (isset($totalReps) && isset($volume) && isset($exercisesMuscles))
        <div class="flex justify-between max-w-lg ">
            @include('partials.chart', [ 'chartTitle' => "Reps vs Muscle Group"])
            @include('partials.muscle-selector', ['chartTitle' => "Reps vs Muscle Group"])
        </div>
    @endif
    @include('partials.dashboard.weighInReminder')
    @if (isset($workout) && isset($exercises))
        @include('livewire.exercise.table')

        @include('partials.cardio-table')
    <!--  // TODO add support for extra workout columns (number_of_circuits, circuit_sets, circuit_reps) -->
    @endif
</div>
