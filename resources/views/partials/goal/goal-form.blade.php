<div>
    <div class="col-span-4 text-2xl">
        @if (isset($goalId))
            Editing Goal - {{$goalBeingEdited->goal_name}}
        @else
            Create Goal
        @endif
    </div>
<!-- Name and Date Input -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalName" value="Name"/>
            <x-jet-input id="goalName" type="text" class="mt-1 block w-full"
                         wire:model.defer="goalName"/>
            <x-jet-input-error for="goalName" class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalValue" value="Value"/>
            <x-jet-input id="goalValue" type="text" class="mt-1 block w-full"
                         wire:model.defer="goalValue"/>
            <x-jet-input-error for="goalValue" class="mt-2"/>
        </div>
    </div>
    <!-- Dates -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalDateStart" value="Start Date"/>
            <x-jet-input id="goalDateStart" type="date" class="mt-1 block w-full"
                         wire:model.defer="goalDateStart"/>
            <x-jet-input-error for="goalDate" class="mt-2"/>
        </div>
        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalDate" value="End Date"/>
            <x-jet-input id="goalDate" type="date" class="mt-1 block w-full"
                         wire:model.defer="goalDate"/>
            <x-jet-input-error for="goalDate" class="mt-2"/>
        </div>
    </div>
    <!-- Description and Value -->
    <div class="grid grid-cols-6gap-4">
        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalDescription" value="Description"/>
            <x-jet-input id="goalDescription" type="text" class="mt-1 block w-full"
                         wire:model.defer="goalDescription"/>
            <x-jet-input-error for="goalDescription" class="mt-2"/>
        </div>
    </div>
    <!-- Goal type and subtype -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
            <x-jet-label for="goalType" value="Type"/>
            <select
                class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                wire:model.debounce100ms="goalType">
                <option value="">Select Type</option>
                @foreach ($goalList as $key => $value)
                    <optgroup label="{{$key}}">
                        @foreach ($value as $subKey => $subValue)
                            <option
                                value="{{$key . ':' . $subKey}}">{{$subValue->goal_type_name}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <x-jet-input-error for="goalType" class="mt-2"/>
        </div>
        @if ($primaryTable)
            <div class="col-span-6 sm:col-span-6 md:col-span-6 lg:col-span-3">
                <x-jet-label for="goalSubType" value="Target"/>
                <select
                    class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    wire:model.debounce100ms="goalSubType">
                    <option value="">Select option</option>
                    @foreach ($primaryTableData as $data)
                        <option
                            value="{{ $data->{$goalList[$accessArray[0]][$accessArray[1]]->table_primary_key} }}">{{
                                        $data->{$goalList[$accessArray[0]][$accessArray[1]]->table_primary_value}
                                        }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="goalSubType" class="mt-2"/>
            </div>
        @endif
    </div>
    <br/>
    <!-- Submit button -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-2 ">
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                wire:click="saveGoal">
                @if (isset($goalId))
                    UPDATE
                @else
                    SAVE
                @endif
            </button>
        </div>
        @if (isset($goalId))
            <div class="col-span-2">
                <button
                    class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition"
                    wire:click="deleteGoal">DELETE
                </button>
            </div>
        @endif
    </div>

    <x-jet-confirmation-modal wire:model="confirmGoalDeletion">
        <x-slot name="title">
            Delete Goal
        </x-slot>

        <x-slot name="content">
            Are you sure you want to select this goal? It is permanent and irreversible.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmGoalDeletion')"
                                    wire:loading.attr="disabled">
                Nevermind
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteConfirmed"
                                 wire:loading.attr="disabled">
                Delete Goal
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    @if (session()->has('message'))
        <br/>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            {{ session('message') }}
        </div>
    @endif
</div>
