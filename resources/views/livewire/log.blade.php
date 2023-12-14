<div>
    <div>
        <div class="text-center text-2xl">
            Workout Log - {{$currentDate->format('d F Y')}}
            <br/>
        </div>
    </div>
    @include('partials.log.workout-log')
    <br/>
    @if (session()->has('deleteButtonMessage'))
        <div class="grid grid-cols-12">
            <div class="col-start-8 col-span-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded "
                 role="alert">
                {{ session('deleteButtonMessage') }}
            </div>
        </div>
    @endif
    <br/>
    @include('partials.log.workout-footer', ['baseUrl' => '/log/'])
    <br/><br/>

    <x-jet-confirmation-modal wire:model="confirmGoalDeletion">
        <x-slot name="title">
            Delete Set
        </x-slot>

        <x-slot name="content">
            Are you sure you want to select this set? It is permanent and irreversible.
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmGoalDeletion')"
                                    wire:loading.attr="disabled">
                Nevermind
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteConfirmed"
                                 wire:loading.attr="disabled">
                Delete Set
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
