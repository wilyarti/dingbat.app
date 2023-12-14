@if (sizeof($sets) <= 0)
    <div>No records found.</div>
@else
<div>
    @foreach ($dateSlots as $dateKey => $sets)
    @for  ($i =0; $i < sizeof($sets); $i++)
        @if ($i ==0)
            <div class="border-b-4 w-auto border-indigo-400">
                <a href="/log/{{date_format(new DateTime($dateKey), 'd-m-Y')}}">
                    {{$dateKey}}
                </a>
            </div>
            <div class="w-auto">
                @endif
                <div class="flex  gap-4 flex-row-reverse pr-8">
                    <div>{{$sets[$i]->reps}}<span class="text-xs text-gray-400"> reps</span></div>
                    <div>{{$sets[$i]->weight}} <span class="text-xs text-gray-400"> kgs</span></div>
                    @if(isset($oneRepMaxDB[$sets[$i]->exercise_id]))
                        @if($oneRepMaxDB[$sets[$i]->exercise_id]['set_id'] == $sets[$i]->set_id)
                            <div>🏆 ({{intval($oneRepMaxDB[$sets[$i]->exercise_id]['one_rep_max'])}}kg)</div>
                        @endif
                    @endif
                </div>
    @endfor
    @endforeach
</div>
@endif
