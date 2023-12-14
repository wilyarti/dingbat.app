<div>
    <div class="text-center text-2xl">
        Plan Creator
    </div>
    @include('partials.plan.wizard')

    @if ($step == 0)
        @include('partials.plan.select-plan')
    @elseif ($step == 1)
        @if ($subStep)
            @include('partials.plan.select-plan-substep')
        @endif
    @elseif ($step == 2)
        @if ($subStep)
            @include('partials.plan.select-workouts-substep')
        @endif
        <br/>
    @else
        @if (session()->has('doneMessage'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('doneMessage') }}
            </div>
            <br/>
        @endif
    <!--
            <br/>
            <button wire:click="runLogic()">RUN AGAIN</button>
            <br/>
            -->
    @endif
    @if ($step < 3)
    <!-- Footer -->
        <div>
            <div class="grid grid-cols-4 gap-2">
                <button wire:click="decrementStep"
                        class="inline-flex items-center px-2 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    PREV
                </button>
                @if ($step != 0)
                    <button wire:click="downloadPlan"
                            class="inline-flex items-center px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        DOWNLOAD
                    </button>
                    <button wire:click="savePlan"
                            class="inline-flex items-center px-2 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                        SKIP + SAVE
                    </button>
                @endif
                <button wire:click="incrementStep"
                        class="inline-flex items-center px-2 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                    NEXT
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
    <br/>
</div>
<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
