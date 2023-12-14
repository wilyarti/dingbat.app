<div>
    @if (isset($goalId))
        <div class="col-span-4 text-2xl">Viewing Goal
            @if (isset($goals[$goalSelected]['goal_name']))
                - <a href="/goals/create/{{$goals[$goalSelected]['goal_id']}}">{{$goals[$goalSelected]['goal_name']}}
                    ✏️</a>
            @endif
        </div>
    @else
        <div class="col-span-4 text-2xl">Chose Goal</div>
    @endif
    <br/>
    @if (!isset($goalId))
        @foreach ($goals as $key=>$goal)
            <div class="">
                <a href="/goals/view/{{$goal->goal_id}}">{{$goal->goal_name}}
                    - {{date_format (new DateTime($goal->date), 'd M Y')}}</a>
            </div>
        @endforeach
    @else

    @endif
    @if (session()->has('deleteButtonMessage'))
        <br/>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            {{ session('deleteButtonMessage') }}
        </div>
    @endif
    <br/>
    <!-- Submit button -->
    @if (isset($goals[$goalSelected]->goal_id))
        @if (!isset($goalId))
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-2">
                    <a
                        href="/goals/view/{{$goals[$goalSelected]->goal_id}}"
                    >
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >VIEW
                        </button>
                    </a>
                </div>
            </div>
        @endif

    @endif

    @if ($tableData && $currentGoal)
        @include('partials.goal.goal-chart')
    @else
    @endif
</div>
