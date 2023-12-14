<div>
    <div class="w-auto">
        @foreach ($setSlots as $slot)
            @foreach ($slot as $exercises)
                @for  ($i =0; $i < sizeof($exercises); $i++)
                    @if ($i ==0)
                        <a href="/exercise/view/{{$exercises[$i]->exercise_id}}">
                            <div class="border-b-4 w-auto border-indigo-400">
                                <span class="text-red-400">
                                    {{chr(64 + $exercises[$i]->exercises_index + 1)}}:
                                </span>
                                {{$exercises[$i]->exercise_name}}
                                <span class="text-xs text-gray-400 pl-4">({{$exercises[$i]->muscle_name}})</span>

                            </div>
                        </a>
                    @endif
                    <div
                        @if ($enableEditing)
                        wire:click="edit({{$exercises[$i]->set_id}})"
                        @endif
                        class="flex  gap-4 flex-row-reverse pr-8">
                        <div>{{$exercises[$i]->reps}}<span class="text-xs text-gray-400"> reps</span></div>
                        <div>{{$exercises[$i]->weight}} <span class="text-xs text-gray-400"> kgs</span></div>
                        @if(isset($oneRepMaxDB[$exercises[$i]->exercise_id]))
                            @if($oneRepMaxDB[$exercises[$i]->exercise_id]['set_id'] == $exercises[$i]->set_id)
                                <div>🏆 ({{intval($oneRepMaxDB[$exercises[$i]->exercise_id]['one_rep_max'])}}kg)</div>
                            @endif
                        @endif
                        @if ($enableEditing)
                            @if ($editSet == $exercises[$i]->set_id)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            @endif
                        @endif

                    </div>
                @endfor
            @endforeach
        @endforeach
    </div>
</div>
