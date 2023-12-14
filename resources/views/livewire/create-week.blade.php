<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-1">
        <div class="bg-white overflow-auto">
            <p class="text-xl pb-3 flex items-center">
            <div class="text-center text-2xl">
                @if (isset($week->week_id))
                    Updating week: {{$week->week_name}}'{{$week->week_id}}'
                @else
                    Creator
                @endif
            </div>

            <form wire:submit.prevent="createNewWeek">
                <div class="px-4 py-5 bg-white grid-cols-1 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                   @if (isset($weekID))
                    <x-jet-label for="selectedWeekId" value="{{ __('Select Week') }}"/>
                    <select wire:model.defer="selectedWeekId"
                            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option value="{{null}}">Disable Edit Mode</option>
                        @if (isset($weeks))
                            @foreach ($weeks as $week)
                                <option value="{{$week->week_id}}">{{$week->week_id}}{{$week->week_name}}</option>
                            @endforeach
                        @endif
                    </select>

                    <x-jet-input-error for="selectedWeekId" class="mt-2"/>
                    @endif
                    <!-- Name -->
                    <div class="max-w-md col-span-6 sm:col-span-4">
                        <x-jet-label for="name" value="{{ __('Name') }}"/>
                        <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name"
                        />
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>

                    <!-- Number Of Days -->
                    <div class="max-w-md col-span-6 sm:col-span-4">
                        <x-jet-label for="numberOfDays" value="{{ __('Number Of Days') }}"/>
                        <x-jet-input id="numberOfDays" type="number" class="mt-1 block w-full"
                                     wire:model.defer="numberOfDays"/>
                        <x-jet-input-error for="numberOfDays" class="mt-2"/>
                    </div>

                    <!-- Number Of Workouts -->
                    <div class="max-w-md col-span-6 sm:col-span-4">
                        <x-jet-label for="numberOfWorkouts" value="{{ __('Number Of Workouts') }}"/>
                        <x-jet-input id="numberOfWorkouts" type="number" class="mt-1 block w-full"
                                     wire:model.defer="numberOfWorkouts"/>
                        <x-jet-input-error for="numberOfWorkouts" class="mt-2"/>
                    </div>

                    @for ($i =0; $i < $numberOfWorkouts; $i++)
                        <x-jet-label for="workoutIds.{{$i}}" value="{{ __('Workout ' . ($i +1)) }}"/>
                        <select wire:model.defer="workoutIds.{{$i}}"
                                class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="{{null}}">Select workout</option>
                            @if(isset($workouts))
                                @foreach ($workouts as $workout)
                                    @if(isset($workout->workout_id))
                                    <option value="{{$workout->workout_id}}">{{$workout->workout_id}}{{}}</option>
                                    @endif
                                        @endforeach
                            @endif
                        </select>
                        <x-jet-input-error for="workoutIds.{{$i}}" class="mt-2"/>
                    @endfor

                    <br/>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    >
                        @if ($editMode)
                            Update
                        @else
                            Create
                        @endif

                    </button>
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>
</div>

