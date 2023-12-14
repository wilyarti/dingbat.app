<div>
    <!-- Error -->
@if (isset($error))
    @include('partials.dashboard.error')
    <!-- Workout Day -->
@elseif (isset($workout))
    @include('partials.dashboard.workoutday')
    <!-- Cardio Day -->
@elseif (isset($cardio))
    @include('partials.dashboard.cardio')
    <!-- Rest Day -->
@else
    @include('partials.dashboard.restday')
@endif
@if (isset($workout) && isset($exercises))
    @include('partials.plan-progress-bar')
@endif
<!-- Footer -->
    @if (isset($prevDay) && isset($nextDay) && isset($curDay))
        @include('partials.footer', ['baseUrl' => '/dashboard/'])
        <br/>
        <br/>
    @endif
</div>
