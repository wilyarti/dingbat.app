<div>
    <div>
        <div class="text-center text-2xl">
            Wilks Calculator
            <br/>
        </div>
    </div>


    <div class="border-b-4 w-auto pb-4 border-pink-400">
        <div class="grid grid-cols-12 gap-4">
            <!-- Male/Female -->
            <div class="col-span-4 sm:col-span-4 md:col-span-4 lg:col-span-3">
                <div>
                    <x-jet-label for="form.male" value="Male"/>
                    <input wire:model="form.male"
                           name="Male"
                           class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                           type="radio">
                    <x-jet-input-error for="form.male" class="mt-2"/>
                </div>
                <div>
                    <x-jet-label for="form.female" value="Female"/>
                    <input wire:model="form.female"
                           name="Female"
                           class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                           type="radio">
                    <x-jet-input-error for="form.female" class="mt-2"/>
                </div>

                <div>
                    <x-jet-label for="form.imperial" value="Imperial"/>
                    <input wire:model="form.imperial"
                           name="Imperial"
                           class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                           type="checkbox">
                    <x-jet-input-error for="form.imperial" class="mt-2"/>
                </div>
            </div>
            <!-- Fields -->
            <div class="col-span-8 sm:col-span-8 md:col-span-8 lg:col-span-3">
                @foreach (['Body Weight' => 'bodyWeight','Lifted Weight' => 'liftedWeight'] as $key => $value)
                        @if ($form['imperial'] == "on")
                        <x-jet-label for="form.{{$value}}" value="{{$key}} lbs" />
                        @else
                        <x-jet-label for="form.{{$value}}" value="{{$key}} kgs" />
                        @endif

                    <x-jet-input id="form.{{$value}}" type="number" class="mt-1 block w-full"
                                 placeholder="0.0" wire:model.debounce.100ms="form.{{$value}}"/>
                    <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                @endforeach
            </div>
        </div>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4 sm:col-span-4 md:col-span-4">
                <div>
                    <br/>
                    <button wire:click="calculate()"
                            class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        CALC
                    </button>
                </div>
            </div>
            <div class="col-span-8 sm:col-span-8 md:col-span-8">
                <div>
                    <x-jet-label for="form.answer" value="Answer:"></x-jet-label>
                    <input placeholder="0.0" disabled="disable"
                           class=" border-l-0 border-r-0 border-t-0 border-b-2 border-pink mt-1 block w-full"
                           id="form.jp7"
                           type="text"
                           wire:model="form.answer"/>
                </div>
            </div>
        </div>
        @if (session()->has('message'))
            <br/>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                 role="alert">
                {{ session('message') }}
            </div>
            <br/>
        @endif
    </div>
</div>
</div>
</div>
