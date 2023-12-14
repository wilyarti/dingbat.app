<div>
    <x-jet-label for="weekSelected" value="{{ __('Original Plan') }}"/>
    <select wire:model="weekSelected"
            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        @if (isset($weekSettingsArray))
            @foreach ($weekSettingsArray as $key=> $week)
                <option value="{{$key}}">{{$week['week_name']}}</option>
            @endforeach
        @endif
    </select>
    <x-jet-label for="workoutSelected" value="{{ __('Original Plan') }}"/>
    <select wire:model="workoutSelected"
            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        @if (isset($workoutSettingsArray[$weekSelected]))
            @foreach ($workoutSettingsArray[$weekSelected] as $key=> $workout)
                <option value="{{$key}}">{{$workout['workout_name']}}</option>
            @endforeach
        @endif
    </select>
    <br/>
    <div>
        <br/>
        <x-jet-form-section submit="save">
            <x-slot name="title">
                {{ __('Specify the week specific settings') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Change week specific settings.') }}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-12">
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.workout_name"
                                     value="Plan Name"/>
                        <x-jet-input id="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.workout_name"
                                     type="text" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.workout_name"/>
                        <x-jet-input-error
                            for="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.workout_name"
                            class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label
                            for="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.number_of_exercises"
                            value="Number Of Exercises"/>
                        <x-jet-input
                            id="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.number_of_exercises"
                            type="number" class="mt-1 block w-full"
                            wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.number_of_exercises"/>
                        <x-jet-input-error
                            for="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.number_of_exercises"
                            class="mt-2"/>
                    </div>
                    @for ($i = 0; $i < $workoutSettingsArray[$weekSelected][$workoutSelected]['number_of_exercises']; $i++)
                        <div>
                            <x-jet-label
                                for="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.exercises.{{$i}}"
                                value="Select Exercise for Workout: {{$i+1}}"/>
                            <select
                                wire:model="workoutSettingsArray.{{$weekSelected}}.{{$workoutSelected}}.exercises.{{$i}}"
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
                    @endfor
                </div>
            </x-slot>
        </x-jet-form-section>
    </div>

</div>
