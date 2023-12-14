<div>
    <br/>
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            {{ __('Clone plan') }}
        </x-slot>
        <x-slot name="description">
            {{ __('Clone a plan and assign to your profile. If you want to swap your existing plan: ') }}
            <br/> <a href="/plan/histroy">Click here.</a>
        </x-slot>
        <x-slot name="form">
            <div class="col-span-12">
                <div class="grid gap-4 grid-cols-8">
                    <div class="col-span-4">
                        <x-jet-label for="plan" value="{{ __('Plan') }}"/>
                        <select wire:model="plan"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            @foreach ($planList as $key => $thisPlan)
                                @if ($thisPlan['user_id'] == $user->id || $thisPlan['user_id'] == 0)
                                    <option value="{{$key}}">
                                        @if ($thisPlan['user_id'] == $user->id)
                                            ME:
                                        @elseif ($thisPlan['user_id'] == 0)
                                            ROOT:
                                        @else
                                            LOCAL:
                                        @endif
                                        ({{$thisPlan->plan_id}}) {{$thisPlan->plan_name}}
                                        - {{date_format(new DateTime($thisPlan->updated_at, $user->time_zone), 'd M y')}}</option>
                                @endif
                                @if ($thisPlan['owner'] == -1 && $showGlobalPlanBoolean)
                                    <option value="{{$key}}">
                                        @if ($thisPlan['user_id'] == $user->id)
                                            ME:
                                        @elseif ($thisPlan['user_id'] == 0)
                                            ROOT:
                                        @else
                                            GLOBAL:
                                        @endif
                                        ({{$thisPlan->plan_id}}) {{$thisPlan->plan_name}}
                                        - {{date_format(new DateTime($thisPlan->updated_at, $user->time_zone), 'd M y')}}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-jet-input-error for="plan" class="mt-2"/>
                    </div>
                    <div class="col-span-2">
                        <x-jet-label for="start_date" value="{{ __('Start Date') }}"/>
                        <input
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            value="start_date" type="date" wire:model="start_date" class="mt-1 block w-full">
                        <x-jet-input-error for="start_date" class="mt-2"/>
                    </div>
                    <div class="col-span-2">
                        <x-jet-label
                            for="showGlobalPlanBoolean"
                            value="Show Global Plans"/>
                        <input
                            wire:model="showGlobalPlanBoolean"
                            name="showGlobalPlanBoolean"
                            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            type="checkbox">
                    </div>
                </div>
                <br/>
                <div class="col-span-12 text-2xl">Description</div>
                <div class="col-span-12">
                    @if (isset($planList[$plan]->description))
                        {!! $planList[$plan]->description !!}
                    @endif
                </div>
                <xslot>
                    <div class="grid gap-4 grid-cols-8">
                        @if (session()->has('message'))
                            <div
                                class="col-span-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                </xslot>
            </div>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="submit">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button wire:loading.attr="disabled" wire:target="photo">
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
            <x-jet-input-error for="date" class="mt-2"/>
        </x-slot>
    </x-jet-form-section>
</div>
