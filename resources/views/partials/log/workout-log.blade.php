<div>
    <div class="w-auto">
        @foreach ($setSlots as $slot)
            @foreach ($slot as $exercises)
                @for  ($i =0; $i < sizeof($exercises); $i++)
                    @if ($i ==0)
                        <a href="/exercise/view/{{$exercises[$i]->exercise_id}}">
                            <div class="border-b-4 w-auto border-indigo-400">
                                <b>{{chr(64 + $exercises[$i]->exercises_index + 1)}}</b>: {{$exercises[$i]->exercise_name}}
                            </div>
                        </a>
                    @endif
                    <div
                        class="flex  gap-4 flex-row-reverse pr-8">
                        @if ($editMode)
                            <div class="text-red-500" wire:click="delete({{$exercises[$i]->set_id}})"
                                 style="width: 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                                </svg>
                            </div>
                        @endif
                        <div>{{$exercises[$i]->reps}}<span class="text-xs text-gray-400"> reps</span></div>
                        <div>{{$exercises[$i]->weight}} <span class="text-xs text-gray-400"> kgs</span></div>
                        @if(isset($oneRepMaxDB[$exercises[$i]->exercise_id]))
                            @if($oneRepMaxDB[$exercises[$i]->exercise_id]['set_id'] == $exercises[$i]->set_id)
                                <div>🏆 ({{intval($oneRepMaxDB[$exercises[$i]->exercise_id]['one_rep_max'])}}kg)</div>
                            @endif
                        @endif
                    </div>
                @endfor
            @endforeach
        @endforeach
    </div>
</div>
