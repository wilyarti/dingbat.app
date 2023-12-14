<div>
    <div>
        <p class="text-xl pb-3 flex items-center">
        <div class="text-center text-2xl">
            Plan History
            <br/>
        </div>
        <br/>
    </div>
    @foreach ($activePlanList as $plan)
        @if ($activePlan->active_plan_id == $plan->active_plan_id)
            (ACTIVE) -
        @endif
        <a href="/plan/view/{{$plan->plan}}">
            ({{$plan->active_plan_id}}) {{$plan->plan_name}}
            - {{date_format( new DateTime($plan->start_date), 'd M y')}}</a><br/>

    @endforeach

    <x-jet-label for="planSelected" value="{{ __('Plan') }}"/>
    <select wire:model="planSelected"
            class=" max-w-md  mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        <option value="">Select active plan</option>
        @foreach ($activePlanList as $plan)
            <option value="{{$plan->active_plan_id}}">({{$plan->active_plan_id}}
                ) {{$plan->plan_name}}</option>
        @endforeach

    </select>

    @if ($planSelected)
        <x-jet-confirmation-modal wire:model="confirmPlanDeletion">
            <x-slot name="title">
                Delete Plan
            </x-slot>

            <x-slot name="content">
                Are you sure you want to select this plan? It is permanent and irreversible.
                Your training log will remain, but you will no longer be able to view plan statistics as
                each
                plan is uniquely generated and applied to your profile....
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
    @endif
    <br/>
    <button wire:click="deleteButton"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        DELETE
    </button>
    <button wire:click="makeActive"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        Make Active
    </button>

    @if (session()->has('message'))
        <br/>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            {{ session('message') }}
        </div>
    @endif
</div>
