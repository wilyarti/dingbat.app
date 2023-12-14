<div class=" relative pt-1">
    <div class="flex mb-2 items-center justify-between">
        <div>
          <span
              class="text-xs font-semibold inline-block py-1 px-2 uppercase text-indigo-600 bg-indigo-100">
            {{$workoutNumber}} of {{$planWorkouts}} workouts
          </span>
        </div>
        <div class="text-right">
          <span class="text-xs font-semibold inline-block text-indigo-600">
            {{number_format($percentage,2)}}%
          </span>
        </div>
    </div>
    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
        <div style="width:{{$percentage}}%"
             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
    </div>
</div>
