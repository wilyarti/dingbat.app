<div class="">
    <div class="flex flex-col table striped hovered main-first-cell ">
        <div class="thead">
            <div class="tr">
                <div class="td">Exercise {{$oneRepMax}}</div>
                <div class="td">Weight</div>
                <div class="td">Reps</div>
            </div>
        </div>
        @if ($sets)
            @foreach ($sets as $set)
                @php
                    $oneRepMaxNotSet = true;
                @endphp
                <div class="tbody">
                    <div wire:click="edit({{$set->set_id}})"
                         @if ($editSet == $set->set_id)
                         class="tr bg-green-400"
                         @else
                         class="tr"
                        @endif
                    >
                        <div class="td mt-1 block w-full">
                            @if (isset($set->exercise_name))
                                {{$set->exercise_name}}

                                @if ($oneRepMax && $oneRepMaxNotSet)
                                    @if(intval($set->one_rep_max) >= $oneRepMax)
                                        🏆
                                    @endif
                                        @php
                                            $oneRepMaxNotSet = false;
                                        @endphp
                                @endif
                            @endif
                        </div>
                        <div class="td ">
                            @if (isset($set->weight))
                                {{$set->weight}}
                            @endif
                        </div>
                        <div class="td">
                            @if (isset($set->reps))
                                {{$set->reps}}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
