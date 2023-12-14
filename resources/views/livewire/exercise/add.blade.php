<div>
    <div>
        <div class="text-center text-2xl"> Workouts <b>
            <!--{{chr(64 + $exercisesIndex + 1)}}--></b>:
            @if ($thisDate)
                for {{$thisDate->format('d F Y')}}
            @endif
        </div>
        @if (!isset($thisPlan))
            <br/>
            <div class="text-center">MUSCLE</div>
            <div class="flex flex-row justify-center gap-4">
                <select id="exercise" wire:model="selectedMuscle" wire:change="muscleChanged"
                        class="bg-transparent  font-semibold py-2 px-4 border border-gray-400 hover:border-green-900 rounded">
                    @foreach($muscles as $muscle)
                        <option
                            value="{{$muscle->muscle_id}}">{{$muscle->muscle_name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="flex flex-row justify-center gap-4">
            <x-jet-input-error class="text-center" for="selectedMuscle" class=" "/>
        </div>
        <br/>
        <div class="text-center">EXERCISE</div>
        <div class="flex flex-row justify-center gap-4">
            <select id="exercise" wire:model="selectedExercise"
                    class="overflow-hidden font-semibold py-2 px-4 border border-gray-400 hover:border-green-900 rounded">
                <option value="">Select Exercise</option>
                @foreach($exerciseDB[0] as $entry)
                    <option
                        value="{{$entry->exercise_id}}">{{$entry->exercise_name}}</option>
                @endforeach
            </select>
            <br/>
        </div>
        <div class="flex flex-row justify-center gap-4">
            <x-jet-input-error class="text-center" for="selectedExercise" class=" "/>
        </div>
    </div>
    <br/>
    <div>
        <div class="text-center">WEIGHT (kgs)</div>
        <div class="flex flex-row justify-center gap-2">
            <button wire:click="decrementWeight"
                    class="bg-transparent hover:bg-red-500 text-red-500 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
            <input wire:model.debounce.100ms="weight"
                   class="bg-transparent  font-semibold border border-gray-400 hover:border-green-900 rounded"
                   type="number">
            <button wire:click="incrementWeight"
                    class="bg-transparent hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>
    </div>
    @error('weight') <br/>
    <div
        class="max-w-lg mx-auto justify-center error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded ">{{ $message }}</div> @enderror

    <br/>
    <div>
        <div class="text-center">REPS</div>
        <div class="flex flex-row justify-center gap-2">
            <button wire:click="decrementReps"
                    class="bg-transparent hover:bg-red-500 text-red-500 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
            <input  id="reps" wire:model.debounce.100ms="reps"
                   class="bg-transparent  font-semibold border border-gray-400 hover:border-green-900 rounded"
                   type="number">
            <button wire:click="incrementReps"
                    class="bg-transparent hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>
    </div>
    @error('reps') <br/>
    <div
        class="max-w-lg mx-auto justify-center error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded ">{{ $message }}</div> @enderror

    <br/>
    <div>
        <div class="flex flex-row justify-center gap-4">
            <button wire:click="save"
                    class="bg-transparent hover:bg-green-500 text-green-500 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif ($editMode)
                    UPDATE
                @else
                    ADD
                @endif
            </button>
            <button wire:click="clear"
                    class="bg-transparent hover:bg-red-500 text-red-500 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                @if ($editMode)
                    DELETE
                @else
                    RESET
                @endif
            </button>
            <button @if (!$autoLoadBoolean)
                    style="display: none;"
                    @endif
                    wire:click="autoload"
                    class="bg-transparent hover:bg-red-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-gray-400 hover:border-transparent rounded">
                AUTOLOAD
            </button>
        </div>
    </div>
    <br/>
    @include('partials.workout-summary', ['setSlots'=>$setSlots, 'enableEditing' => true])
    @include('partials.add.add-script')
</div>
