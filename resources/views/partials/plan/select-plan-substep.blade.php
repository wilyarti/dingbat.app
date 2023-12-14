<div>
    <br/>
    <div>
        <br/>
        <div>
            <div class="text-center text-2xl" name="title">
                Settings For Week {{$subStep}}/{{$planSettings['number_of_weeks']}}.
            </div>
            <div class="col-span-12">
                <div class="grid grid-cols-6">
                    <div class="col-span-4">
                        <x-jet-label for="weekSettingsArray.{{$subStep -1}}.week_name" value="Name"/>
                        <x-jet-input id="weekSettingsArray.{{$subStep -1}}.week_name" type="text"
                                     class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettingsArray.{{$subStep -1}}.week_name"/>
                        <x-jet-input-error for="weekSettingsArray.{{$subStep -1}}.week_name" class="mt-2"/>
                    </div>
                </div>

                <div class="grid grid-cols-6">
                    <div class="col-span-4">
                        <x-jet-label for="weekSettingsArray.{{$subStep -1}}.number_of_days" value="Number Of Days"/>
                        <x-jet-input id="weekSettingsArray.{{$subStep -1}}.number_of_days" type="number"
                                     class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettingsArray.{{$subStep -1}}.number_of_days"/>
                        <x-jet-input-error for="weekSettingsArray.{{$subStep -1}}.number_of_days" class="mt-2"/>
                    </div>
                </div>
                <div>
                    @if (session()->has('messageNumberOfDays'))
                        <br/>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                             role="alert">
                            {{ session('messageNumberOfDays') }}
                        </div>
                    @endif
                </div>
            <!--
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="weekSettingsArray.{{$subStep -1}}.number_of_workouts"
                                     value="Number Of Workouts"/>
                        <x-jet-input disabled="disabled" id="weekSettingsArray.{{$subStep -1}}.number_of_workouts"
                                     type="number" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettingsArray.{{$subStep -1}}.number_of_workouts"/>
                        <x-jet-input-error for="weekSettingsArray.{{$subStep -1}}.number_of_workouts" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="weekSettingsArray.{{$subStep -1}}.number_of_cardio"
                                     value="Number Of Cardio Days"/>
                        <x-jet-input disabled="disabled" id="weekSettingsArray.{{$subStep -1}}.number_of_cardio"
                                     type="number" class="mt-1 block w-full"
                                     wire:model.debounce.1000ms="weekSettingsArray.{{$subStep -1}}.number_of_cardio"/>
                        <x-jet-input-error for="weekSettingsArray.{{$subStep -1}}.number_of_cardio" class="mt-2"/>
                    </div>
-->
                <div>
                    @if (session()->has('messageNumberOfWorkouts'))
                        <br/>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                             role="alert">
                            {{ session('messageNumberOfWorkouts') }}
                        </div>
                    @endif
                </div>
                <div class="col-span-12 text-2xl">Workout Day Selector</div>
                <div class="flex gap-4">
                    @for ($i =0; $i < $weekSettingsArray[$subStep -1]['number_of_days']; $i++)
                        <div class="flex-auto">
                            <label
                                for="weekSettingsArray.{{$subStep -1}}.workouts_indexs.{{$i}}">Day {{$i+1}}</label>
                            <input wire:model="weekSettingsArray.{{$subStep -1}}.workouts_indexs.{{$i}}"
                                   name="Day {{$i}}"
                                   class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                   type="checkbox">
                        </div>
                    @endfor
                </div>

                <div class="col-span-12 text-2xl">Cardio Day Selector</div>
                <div class="flex gap-4">
                    @for ($i =0; $i < $weekSettingsArray[$subStep -1]['number_of_days']; $i++)
                        <div class="flex-auto">
                            <label for="weekSettingsArray.{{$subStep -1}}.cardio_indexs.{{$i}}">Day {{$i+1}}</label>
                            <input wire:model="weekSettingsArray.{{$subStep -1}}.cardio_indexs.{{$i}}"
                                   name="Day {{$i}}"
                                   class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                   type="checkbox">
                        </div>
                    @endfor
                </div>

                <div>
                    @if (session()->has('messageWeeks'))
                        <br/>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                             role="alert">
                            {{ session('messageWeeks') }}
                        </div>
                    @endif
                </div>
                @for ($i =0 ; $i < $weekSettingsArray[$subStep -1]['number_of_cardio']; $i++)
                    <div class="grid grid-cols-6">
                        <div class="col-span-4">
                            <x-jet-label
                                for="weekSettingsArray.{{$subStep -1}}.cardio.{{$i}}"
                                value="Cardio Day {{$i+1}}:"/>
                            <select
                                wire:model="weekSettingsArray.{{$subStep -1}}.cardio.{{$i}}"
                                class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                @foreach ($cardioList as $cardio)
                                    <option
                                        value="{{$cardio}}">{{$cardio}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
                <div class="grid grid-cols-6">
                    <div class="col-span-4">
                        <x-jet-label
                            for="adapter"
                            value="Plan Adapter:"/>
                        <select
                            wire:model="adapter"
                            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            @foreach ($adapterList as $key => $adapter)
                                <option
                                    value="{{$key}}">{{$adapter}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br/>
            </div>
        </div>
    </div>
</div>
