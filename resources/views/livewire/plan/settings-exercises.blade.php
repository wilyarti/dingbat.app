<div>
    <br/>
    <x-jet-form-section submit="save">
        <x-slot name="title">
            {{ __('Equipment settings') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Disable or enable equipment for your plan.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-12">
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($equipmentSettingsList as $key => $value)
                        <div class=" col-span-2 md:col-span-1">
                            <label for="{{$value}}">{{$key}}</label>
                        </div>
                        <div class=" col-span-2 md:col-span-1">
                            <input wire:model="{{$value}}" name="{{$value}}"
                                   class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                   type="checkbox">
                        </div>
                    @endforeach
                    <br/>
                    <br/>
                    <div class=" col-span-4">
                        <button wire:click="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            SAVE
                        </button>
                    </div>

                    <div class=" col-span-2">
                        @if (session()->has('message'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                {{ session('message') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </x-slot>
    </x-jet-form-section>

    <br/>
    <x-jet-form-section submit="save">
        <x-slot name="title">
            {{ __('Exercise settings') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Disable or enable exercises for your plan.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-12">
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($exerciseSettingsList as $key => $value)
                        <div class=" col-span-2 md:col-span-1">
                            <label for="{{$value}}">{{$key}}</label>
                        </div>
                        <div class=" col-span-2 md:col-span-1">
                            <input wire:model="{{$value}}" name="{{$value}}"
                                   class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                   type="checkbox">
                        </div>
                    @endforeach
                    <br/>
                    <div class=" col-span-4">
                        <button wire:click="submit2"
                                class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            SAVE
                        </button>
                    </div>
                    <div class=" col-span-2">
                        @if (session()->has('message2'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                {{ session('message2') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>
    </x-jet-form-section>
</div>
