<x-jet-confirmation-modal wire:model="confirmWorkoutDeletion">
    <x-slot name="title">
        Delete Workout
    </x-slot>

    <x-slot name="content">
        Are you sure you want to select this workout?
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmWorkoutDeletion')"
                                wire:loading.attr="disabled">
            Nevermind
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="deleteWorkoutConfirmed"
                             wire:loading.attr="disabled">
            Delete Workout
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>
