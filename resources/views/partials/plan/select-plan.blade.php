<div>
    <div>
        <br/>
        <div>
            <div class="col-span-12">
                <div class="grid grid-cols-5 gap-1 border-b-8 w-auto border-pink-500 pb-2">
                    <div class="col-span-3">
                        <x-jet-label for="planSelected" value="{{ __('Original Plan') }}"/>
                        <select wire:model="planSelected"
                                class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="">Blank Plan</option>

                            @if (isset($plans))
                                @foreach ($plans as $plan)
                                    @if ($plan['user_id'] == $user->id || $plan['user_id'] == 0 || $plan['owner'] == -1)
                                        <option value="{{$plan['plan_id']}}">

                                            @if ($plan['owner'] == -1)
                                                GLOBAL({{$plan['plan_id']}}):
                                            @elseif ($plan['user_id'] == $user->id)
                                                ME({{$plan['plan_id']}}):
                                            @elseif ($plan['user_id'] == 0)
                                                ROOT({{$plan['plan_id']}}):
                                            @endif

                                            {{$plan['plan_name']}}
                                            - {{date_format(new DateTime($plan['updated_at'], $user->time_zone), 'd M y')}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-span-3">
                    <x-jet-label for="planSettings.plan_name" value="Plan Name"/>
                    <x-jet-input id="planSettings.plan_name" type="text" class="mt-1 block w-full"
                                 wire:model.debounce.1000ms="planSettings.plan_name"/>
                    <x-jet-input-error for="planSettings.plan_name" class="mt-2"/>
                </div>

                <div class="grid grid-cols-6 gap-1  pb-2">
                    <div class="col-span-3">
                        <x-jet-label for="planSettings.number_of_weeks" value="Weeks In Plan"/>
                        <x-jet-input id="planSettings.number_of_weeks" type="number" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="planSettings.number_of_weeks"/>
                        <x-jet-input-error for="planSettings.number_of_weeks" class="mt-2"/>
                    </div>

                    <div class="col-span-3">
                        <x-jet-label for="weekSettings.number_of_days" value="Days Per Week"/>
                        <x-jet-input id="weekSettings.number_of_days" type="number" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettings.number_of_days"/>
                        <x-jet-input-error for="weekSettings.number_of_days" class="mt-2"/>
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-1  pb-2">
                    <div class="col-span-3">
                        <x-jet-label for="weekSettings.number_of_workouts"
                                     value="Workouts Per Week"/>
                        <x-jet-input id="weekSettings.number_of_workouts" type="number" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettings.number_of_workouts"/>
                        <x-jet-input-error for="weekSettings.number_of_workouts" class="mt-2"/>
                    </div>

                    <div class="col-span-3">
                        <x-jet-label for="workoutSettings.number_of_exercises"
                                     value="Exercises Per Workout"/>
                        <x-jet-input id="workoutSettings.number_of_exercises" type="number"
                                     class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="workoutSettings.number_of_exercises"/>
                        <x-jet-input-error for="workoutSettings.number_of_exercises" class="mt-2"/>
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-1  pb-2">
                    <div class="col-span-2">
                        <label for="copyMode">Copy Workout</label>
                        <input wire:model="copyMode" name="copyMode"
                               class="mt-1 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
                               type="checkbox">
                    </div>
                    <div class="col-span-2">
                        <label for="globalMode">Make Global</label>
                        <input wire:model="globalMode" name="globalMode"
                               class="mt-1 block border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
                               type="checkbox">
                    </div>
                </div>
                <div>
                    @include('partials.plan.description')
                </div>
                @if ($planSelected)
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        Warning: Modifying a plan will result in the week_ids, and workout_ids being
                        regenerated.
                        This will cause
                        these id's to be orphaned and will not show up on your plan record. They will however
                        still
                        stay in your records.
                    </div>
                    <br/>
                @endif
                @if ($planSelected)
                    <x-jet-confirmation-modal wire:model="confirmPlanDeletion">
                        <x-slot name="title">
                            Delete Plan
                        </x-slot>

                        <x-slot name="content">
                            Are you sure you want to select this plan? It is permanent and irreversible.
                        </x-slot>

                        <x-slot name="footer">
                            <x-jet-secondary-button wire:click="$toggle('confirmPlanDeletion')"
                                                    wire:loading.attr="disabled">
                                Nevermind
                            </x-jet-secondary-button>

                            <x-jet-danger-button class="ml-2" wire:click="deleteConfirmed"
                                                 wire:loading.attr="disabled">
                                Delete Plan
                            </x-jet-danger-button>
                        </x-slot>
                    </x-jet-confirmation-modal>

                    <button wire:click="deleteButton"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        DELETE
                    </button>
                @endif
                <br/>
                @if (session()->has('deleteButtonMessage'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('deleteButtonMessage') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
