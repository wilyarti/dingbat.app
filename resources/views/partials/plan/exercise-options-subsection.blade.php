<div
    @if ($workoutSettings['adapter'])
    class="grid grid-cols-6 gap-1"
    @else
    class="grid grid-cols-6 gap-1"
    @endif
>
    <div class="col-span-4">
        <x-jet-label
            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises.{{$i}}"
            value="Exercise: {{$i +1}}"/>
        <select
            wire:model="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises.{{$i}}"
            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
            @if (isset($muscles))
                @foreach ($muscles as $muscle)
                    <optgroup label="{{$muscle->muscle_name}}">
                        @foreach ($exercises as $exercise)
                            @if ($exercise->muscle_id == $muscle->muscle_id)
                                <option
                                    value="{{$exercise->exercise_id}}">{{$exercise['exercise_name']}}</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-span-1">
        <x-jet-label
            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_sets.{{$i}}"
            value="Sets"/>
        <x-jet-input
            id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_sets.{{$i}}"
            type="number" class="mt-1 block w-full"
            wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_sets.{{$i}}"/>
        <x-jet-input-error
            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_sets.{{$i}}"
            class="mt-2"/>
    </div>
    <div class="col-span-1">
        <x-jet-label
            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_reps.{{$i}}"
            value="Reps"/>
        <x-jet-input
            id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_reps.{{$i}}"
            type="number" class="mt-1 block w-full"
            wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_reps.{{$i}}"/>
        <x-jet-input-error
            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.exercises_reps.{{$i}}"
            class="mt-2"/>
    </div>
</div>
<div
    @if ($i % 2)
    class="grid grid-cols-4 gap-4 border-b-4 w-auto border-pink-400 pb-2.5"
    @else
    class="grid grid-cols-4 gap-4 border-b-4 w-auto border-indigo-400 pb-2.5"
    @endif
>
    @if ($adapter)
        <div class="col-span-1">
            <x-jet-label
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.one_rep_max.{{$i}}"
                value="1RM %"/>
            <select
                wire:model="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.one_rep_max.{{$i}}"
                class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                @for ($p = 0; $p < 120; $p+=2.5)
                    <option
                        value="{{$p}}">{{$p}}
                    </option>
                @endfor
            </select>
        </div>
    @endif
    @if ($adapter == 2)
        <div class="col-span-1">
            <x-jet-label
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.time_under_tension.{{$i}}"
                value="TUT"/>
            <x-jet-input
                id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.time_under_tension.{{$i}}"
                type="number" class="mt-1 block w-full"
                wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.time_under_tension.{{$i}}"/>
            <x-jet-input-error
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.time_under_tension.{{$i}}"
                class="mt-2"/>
        </div>
        <div class="col-span-1">
            <x-jet-label
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.rest_per_set.{{$i}}"
                value="RPS"/>
            <x-jet-input
                id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.rest_per_set.{{$i}}"
                type="number" class="mt-1 block w-full"
                wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.rest_per_set.{{$i}}"/>
            <x-jet-input-error
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.rest_per_set.{{$i}}"
                class="mt-2"/>
        </div>
        <div class="col-span-1">
            <x-jet-label
                for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.as_many_reps_as_possible.{{$i}}"
                value="AMRAP"/>
            <input
                wire:model="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.adapter_array.as_many_reps_as_possible.{{$i}}"
                name="AMRAP"
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                type="checkbox">
        </div>
    @endif
</div>
