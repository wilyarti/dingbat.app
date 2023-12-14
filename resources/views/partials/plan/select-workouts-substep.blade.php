<div>
    <br/>
    <div>
        <br/>
        <div>
            <div class="text-center text-2xl" name="title">
                Workout {{$subStep}}/{{$weekSettingsArray[$weekSelected]['number_of_workouts']}},
                Week {{$weekSelected +1 }}/{{$planSettings['number_of_weeks']}}
            </div>
            <div class="">
                <!-- Top drop down/input boxes -->
                <div class="grid grid-cols-7 gap-1 border-b-8 w-auto border-pink-500 pb-2">
                    <div class="col-span-3">
                        <x-jet-label for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.workout_name"
                                     value="Name"/>
                        <x-jet-input id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.workout_name"
                                     type="text" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.workout_name"/>
                        <x-jet-input-error
                            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.workout_name"
                            class="mt-2"/>
                    </div>
                <!-- Change Day Selector - disabled
                    <div class="col-span-1">
                        <x-jet-label
                            for="changeDaySelector"
                            value="Day"/>
                        <select
                            wire:model="changeDaySelector"
                            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            @for ($i = 0; $i < $weekSettingsArray[$weekSelected]['number_of_days']; $i++)
                                @if (!$weekSettingsArray[$weekSelected]['workouts_indexs'][$i] || $workoutIndexWeekDaySelector == $i )
                                    <option
                                        value="{{$i}}">{{$weekDays[$i]}}
                                    </option>
                                @endif
                            @endfor
                    </select>
                    <x-jet-input-error
                        for="changeDaySelector"
                        class="mt-2"/>
                </div>
-->
                    <div class="col-span-2">
                        <x-jet-label
                            for="workoutIndexWeekDaySelector"
                            value="Index"/>
                        <select
                            wire:model="workoutIndexWeekDaySelector"
                            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            @for ($i = 0; $i < $weekSettingsArray[$weekSelected]['number_of_days']; $i++)
                                @if ($weekSettingsArray[$weekSelected]['workouts_indexs'][$i])
                                    <option
                                        value="{{$i}}">{{$weekDays[$i]}}
                                    </option>
                                @endif
                            @endfor
                        </select>
                        <x-jet-input-error
                            for="workoutIndexWeekDaySelector"
                            class="mt-2"/>
                    </div>

                    <div class="col-span-2">
                        <x-jet-label
                            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.number_of_exercises"
                            value="Exercises"/>
                        <x-jet-input
                            id="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.number_of_exercises"
                            type="number" class="mt-1 block w-full"
                            wire:model.debounce.1000ms="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.number_of_exercises"/>
                        <x-jet-input-error
                            for="workoutSettingsArray.{{$weekSelected}}.{{$subStep -1}}.number_of_exercises"
                            class="mt-2"/>
                    </div>
                </div>
                @if (session()->has('moveMessage'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('moveMessage') }}
                    </div>
                @endif
                <br/>
                <!-- Exercises lists -->
                <button type="button" wire:click="toggleExercises"
                        class="item-center inline-flex px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    @if ($showExercises)
                        Hide Workouts ↘️
                    @else
                        Show Workouts ↘️
                    @endif
                </button>
                <p/>
                @if ($showExercises)
                    @for ($i = 0; $i < $workoutSettingsArray[$weekSelected][$subStep -1]['number_of_exercises']; $i++)
                        <div>
                            <div class="">
                                <div class="float-right">
                                    @if ($i != 0)
                                        <button wire:click="shuffleUp({{$weekSelected}}, {{$subStep -1}}, {{$i}})">⬆️
                                        </button>
                                    @endif
                                    @if ($i < $workoutSettingsArray[$weekSelected][$subStep -1]['number_of_exercises'] -1  )
                                        <button wire:click="shuffleDown({{$weekSelected}}, {{$subStep -1}}, {{$i}})">⬇️
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="border-2">
                                @include('partials.plan.exercise-options-subsection')
                            </div>
                        </div>
                    @endfor
                @endif
                <br/>
                @if (session()->has('copyMessage'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('copyMessage') }}
                    </div>
                @endif
            <!-- Lower buttons -->
                <div class="grid grid-cols-4 gap-4">
                    <button wire:click="cloneWorkout"
                            class="item-center inline-flex px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        CLONE WORKOUT
                    </button>

                    <button wire:click="deleteWorkout"
                            class=" px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                        DELETE WORKOUT
                    </button>


                    @if ($weekSettingsArray[$weekSelected]['number_of_workouts'] == $subStep)
                        <button wire:click="copyForwardWeek({{$weekSelected}}, {{$subStep -1}})"
                                class=" px-4 py-2 bg-yellow-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition">
                            COPY WEEK TO NEXT
                        </button>
                        <button wire:click="copyForwardAllWeeks({{$weekSelected}}, {{$subStep -1}})"
                                class=" px-4 py-2 bg-yellow-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition">
                            COPY WEEK TO REST
                        </button>
                    @else
                        <button wire:click="cloneSet({{$weekSelected}}, {{$subStep -1}})"
                                class=" px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            CLONE LAST SET
                        </button>
                        <button wire:click="copyForward({{$weekSelected}}, {{$subStep -1}})"
                                class=" px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            COPY TO NEXT W/O
                        </button>
                    @endif
                </div>

                @include('partials.plan.workouts-delete-modal')
            </div>
        </div>
    </div>
</div>

