<div>
    <div>
        <p class="text-xl pb-3 flex items-center">
        <div class="text-center text-2xl">
            @if ($editMode)
                <div class="col-span-4 text-2xl">Editing Measurement</div>
            @else
                <div class="col-span-4 text-2xl">Recording Measurement</div>
            @endif
        </div>
        <br/>
    </div>
    <div class=" ">
        <form wire:submit.prevent="submit">
            <div class="grid grid-cols-12 gap-4">
            @foreach ($basic as $key => $value)
                <!-- {{$value}} -->
                    <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                        <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                        <x-jet-input id="form.{{$value}}" step="any" type="number" class="mt-1 block w-full"
                                     placeholder="0.0"  wire:model.defer="form.{{$value}}"/>
                        <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                    </div>
            @endforeach

            <!-- date -->
                <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                    <x-jet-label for="form.date" value="Date"/>
                    <x-jet-input id="form.date" type="date" class="mt-1 block w-full"
                                 placeholder="0.0"  wire:model.defer="form.date"/>
                    <x-jet-input-error for="form.date" class="mt-2"/>
                </div>
            </div>
            <br/>
            <div class="col-span-4 text-2xl">Measurements</div>
            <div class="grid grid-cols-12 gap-4">

            @foreach ($measurement as $key => $value)
                <!-- {{$value}} -->
                    <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                        <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                        <x-jet-input id="form.{{$value}}" step="any" type="number" class="mt-1 block w-full"
                                     placeholder="0.0"  wire:model.defer="form.{{$value}}"/>
                        <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                    </div>
                @endforeach
            </div>
            <br/>
            <div class=" text-2xl">Composition</div>
            <div class="grid grid-cols-12 gap-4">

            @foreach ($advanced as $key => $value)
                <!-- {{$value}} -->
                    <div class="col-span-6 sm:col-span-6 md:col-span-4 lg:col-span-3">
                        <x-jet-label for="form.{{$value}}" value="{{$key}}"/>
                        <x-jet-input id="form.{{$value}}" step="any" type="number" class="mt-1 block w-full"
                                     placeholder="0.0"  wire:model.defer="form.{{$value}}"/>
                        <x-jet-input-error for="form.{{$value}}" class="mt-2"/>
                    </div>
                @endforeach
            </div>
            <br/>
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <x-jet-button>
                    @if ($editMode)
                        {{ __('Update') }}
                    @else
                        {{ __('Save') }}
                    @endif
                </x-jet-button>

            </div>
        </form>
    </div>
</div>
