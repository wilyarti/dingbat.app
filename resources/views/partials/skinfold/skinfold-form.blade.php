<form wire:submit.prevent="submit">
    <div>
        <div class="text-center text-2xl">
            Calculate Body Fat % Using Skin Fold
            <br/>
            <br/>
        </div>
        @if ($editMode)
            <div class="col-span-4 text-2xl">Editing Measurement</div>
        @endif
        @if (!$user)
            <br/>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                 role="alert">
                WARNING: you are not logged in. <a href="/login"> LOGIN HERE</a>
            </div>
            <br/>
        @endif
    <!-- Date Input -->
        <div class="grid grid-cols-12 gap-2 ">
            <!-- date -->
            <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                <x-jet-label for="form.date" value="Date"/>
                <x-jet-input id="form.date" type="date" class="mt-1 block"
                             wire:model="form.date"/>
                <x-jet-input-error for="form.date" class="mt-2"/>
            </div>

            <!-- type selector -->
            @if (!$editMode)
                <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                    <x-jet-label for="measurementType" value="Type"/>
                    <select
                        wire:model="measurementType"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        @foreach (['jp3' => "Jackson Pollock 3 Site",'jp7' => "Jackson Pollock 7 Site", 'parillo' => 'Parillo', 'durnin' => "Durnin"] as $key => $value)
                            <option
                                value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                    <div>
                        <x-jet-label for="form.male" value="Male"/>
                        <input wire:model="form.male"
                               name="Male"
                               @if ($editMode)
                                   disabled="disabled"
                               @endif
                               class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                               type="radio">
                        <x-jet-input-error for="form.male" class="mt-2"/>
                    </div>
                    <div>
                        <x-jet-label for="form.female" value="female"/>
                        <input wire:model="form.female"
                               name="Female"
                               @if ($editMode)
                               disabled="disabled"
                               @endif
                               class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                               type="radio">
                        <x-jet-input-error for="form.female" class="mt-2"/>
                    </div>
                </div>
        </div>
        @if ((!$editMode && $measurementType == 'jp3') || ($editMode && $form['jp3'] ))
        <!-- Calculate JP3 -->
            <div class="border-b-4 w-auto pb-4 border-pink-400">
                <div class="col-span-4 text-2xl">Jackson Pollock 3 Site</div>
                <div class="grid grid-cols-12 gap-4 ">
                    @if ($form['male'] == 'on')
                        @foreach ($manJp3Array as $key => $value)
                            <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                                <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                                <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                             placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                                <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                            </div>
                        @endforeach
                    @else
                        @foreach ($womanJp3Array as $key => $value)
                            <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                                <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                                <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                             placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                                <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                            </div>
                        @endforeach
                    @endif
                </div>
                <br/>
                @if (session()->has('jp3'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('jp3') }}
                    </div>
                    <br/>
                @endif
                <div class="grid grid-cols-2 ">
                    <div class="col-span-1">
                        <div class=" grid grid-cols-2">
                            <div class="col-span-1">
                                <button wire:click="calculateJP3()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    CALC
                                </button>
                            </div>
                            @if ($user)
                                <div class="col-span-1">
                                    <button wire:click=saveJP3()"
                                            class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                        @if ($editMode)
                                            UPDATE
                                        @else
                                            SAVE
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <x-jet-label for="form.jp3" value="Answer %"></x-jet-label>
                            <input placeholder="0.0" disabled="disable"
                                   class=" border-l-0 border-r-0 border-t-0 border-b-2 border-pink mt-1 block w-full"
                                   id="form.jp7"
                                   type="text"
                                   wire:model="form.jp3"/>
                        </div>
                    </div>
                </div>
            </div>


        @endif
        @if ((!$editMode && $measurementType == 'jp7') || ($editMode && $form['jp7'] ))
        <!-- Calculate JP7 -->
            <div class="border-b-4 w-auto pb-4 border-pink-400">
                <div class="col-span-4 text-2xl">Jackson Pollock 7 Site</div>
                <div class="grid grid-cols-12 gap-4 ">
                    @foreach ($jp7Array as $key => $value)
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                            <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                            <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                         placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                            <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                        </div>
                    @endforeach
                </div>
                <br/>
                @if (session()->has('jp7'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('jp7') }}
                    </div>
                    <br/>
                @endif
                <div class="grid grid-cols-2 ">
                    <div class="col-span-1">
                        <div class=" grid grid-cols-2">
                            <div class="col-span-1">
                                <button wire:click="calculateJP7()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    CALC
                                </button>
                            </div>
                            @if ($user)
                                <div class="col-span-1">
                                    <button wire:click=saveJP7()"
                                            class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                        @if ($editMode)
                                            UPDATE
                                        @else
                                            SAVE
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <x-jet-label for="form.jp7" value="Answer %"></x-jet-label>
                            <input placeholder="0.0" disabled="disable"
                                   class=" border-l-0 border-r-0 border-t-0 border-b-2 border-pink mt-1 block w-full"
                                   id="form.jp7"
                                   type="text"
                                   wire:model="form.jp7"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ((!$editMode && $measurementType == 'parillo') || ($editMode && $form['parillo'] ))
        <!-- Calculate Parillo -->
            <div class="border-b-4 w-auto pb-4 border-pink-400">
                <div class=" text-2xl">Parillo</div>
                <div class="grid grid-cols-12 gap-4 ">
                @foreach ($parilloArray as $key => $value)
                    <!-- {{$value}} -->
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                            <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                            <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                         placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                            <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                        </div>
                    @endforeach
                </div>
                <br/>
                @if (session()->has('parillo'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('parillo') }}
                    </div>
                    <br/>
                @endif
                <div class="grid grid-cols-2 ">
                    <div class="col-span-1">
                        <div class=" grid grid-cols-2">
                            <div class="col-span-1">
                                <button wire:click="calculateParillo()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    CALC
                                </button>
                            </div>
                            @if ($user)
                                <div class="col-span-1">
                                    <button wire:click=saveParillo()"
                                            class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                        @if ($editMode)
                                            UPDATE
                                        @else
                                            SAVE
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <x-jet-label for="form.parillo" value="Answer"></x-jet-label>
                            <input placeholder="0.0" disabled="disable"
                                   class=" border-l-0 border-r-0 border-t-0 border-b-2 border-pink mt-1 block w-full"
                                   id="form.jp7"
                                   type="text"
                                   wire:model="form.parillo"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ((!$editMode && $measurementType == 'durnin') || ($editMode && $form['durnin'] ))
        <!-- Calculate Durnin -->
            <div class="border-b-4 w-auto pb-4 border-pink-400">
                <div class=" text-2xl">Durnin</div>
                <div class="grid grid-cols-12 gap-4 ">
                @foreach ($durninArray as $key => $value)
                    <!-- {{$value}} -->
                        <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                            <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                            <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                         placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                            <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                        </div>
                    @endforeach
                </div>
                <br/>
                @if (session()->has('durnin'))
                    <br/>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         role="alert">
                        {{ session('durnin') }}
                    </div>
                    <br/>
                @endif
                <div class="grid grid-cols-2 ">
                    <div class="col-span-1">
                        <div class=" grid grid-cols-2">
                            <div class="col-span-1">
                                <button wire:click="calculateDurnin()"
                                        class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    CALC
                                </button>
                            </div>
                            @if ($user)
                                <div class="col-span-1">
                                    <button wire:click=saveDurnin()"
                                            class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                        @if ($editMode)
                                            UPDATE
                                        @else
                                            SAVE
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <x-jet-label for="form.durnin" value="Answer"></x-jet-label>
                            <input placeholder="0.0" disabled="disable"
                                   class=" border-l-0 border-r-0 border-t-0 border-b-2 border-pink mt-1 block w-full"
                                   id="form.jp7"
                                   type="text"
                                   wire:model="form.durnin"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</form>
