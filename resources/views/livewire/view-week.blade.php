<div>
    @if (isset($selectedWeek) && isset($dailySlotVolume[$selectedWeek->week_id]))
        @include('livewire.week-chart', ['dailyVolume'=>$dailyVolume,
                'dailySlotVolume'=>$dailySlotVolume,
                'colorPalette' => $colorPalette])

        <br/>
        @include('partials.week-details', ["allWeeks" => false, "weeks"=> $weeks])
    @else
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            Error: no data for week.
        </div>
    @endif
</div>
