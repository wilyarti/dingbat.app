<div>
    @for ($i = 0; $i < $workout->number_of_exercises; $i++)
        <div class=" border-b-4 w-auto border-indigo-400 ">
            <a class="flex-row"
               @if ($adapterData)
               href="/plan/{{$planId}}/week/{{$weekId}}/workout/{{$workoutId}}/exercise/{{$exercises[$i]->exercise_id}}/slot/{{$i}}/reps/{{$workout->exercises_reps[$i]}}/weight/{{$adapterData[$i]}}"
               @else
               href="/plan/{{$planId}}/week/{{$weekId}}/workout/{{$workoutId}}/exercise/{{$exercises[$i]->exercise_id}}/"
                @endif
            >
                @if (isset($completed[$i]))
                    @if ($completed[$i])
                        <div>
                            <div class="float-left ">
                                <span class="text-red-400">{{chr(64 + $i + 1)}}:</span>
                                <strike>{{$exercises[$i]->exercise_name}}</strike>
                            </div>
                            <span class="text-xs text-gray-400 pl-4">({{$exercisesMuscles[$i]}})</span>
                        </div>
                    @else
                        <div>
                            <div class="float-left">
                                <span
                                    class="text-red-400">{{chr(64 + $i + 1)}}:</span> {{$exercises[$i]->exercise_name}}
                            </div>
                            <span class="text-xs text-gray-400 pl-4">({{$exercisesMuscles[$i]}})</span>
                        </div>
                    @endif
                @endif
            </a>
        </div>
        <div class="w-auto">

            <div class="flex text-gray-900 gap-4 flex-row-reverse pr-8 ">
                <div class="grid grid-cols-4 gap-4 ">
                    <div class="col-span-2">
                        <span class="text-xs text-gray-400">SETS: </span>{{$workout->exercises_sets[$i]}}
                    </div>
                    <div class="col-span-2">
                        <span class="text-xs text-gray-400">REPS: </span>{{$workout->exercises_reps[$i]}}
                    </div>
                </div>
            </div>
            @if(isset($workout['adapter_array']) && $planId > 6)
                @php
                    $counter = 0;
                    if (isset($workout['adapter_array']['as_many_reps_as_possible'][$i])) {
                        if ($workout['adapter_array']['as_many_reps_as_possible'][$i] == true) {
                            $counter++;
                        }
                    }
                    if (isset($workout['adapter_array']['one_rep_max'][$i])) {
                        $counter++;
                    }
                    if (isset($workout['adapter_array']['rest_per_set'][$i])) {
                        if($workout['adapter_array']['rest_per_set'][$i]> 1) {
                            $counter++;
                        }
                    }
                    if (isset($workout['adapter_array']['time_under_tension'][$i])) {
                        if($workout['adapter_array']['time_under_tension'][$i]> 1) {
                            $counter++;
                        }
                    }
                @endphp
                <div class="flex text-gray-900 gap-4 flex-row-reverse pr-8 ">
                    <div class="grid grid-cols-{{$counter*2}} gap-4 ">
                        @if (isset($workout['adapter_array']['as_many_reps_as_possible'][$i]))
                            @if($workout['adapter_array']['as_many_reps_as_possible'][$i] == true)
                                <div class="col-span-2">
                                    <span class="text-xs text-gray-400">AMRAP</span> +
                                </div>
                            @endif
                        @endif
                        @if (isset($workout['adapter_array']['rest_per_set'][$i]))
                            @if($workout['adapter_array']['rest_per_set'][$i]> 1)
                                <div class="col-span-2">
                                    <span class="text-xs text-gray-400">RPS: </span>
                                    {{$workout['adapter_array']['rest_per_set'][$i]}}
                                </div>
                            @endif
                        @endif

                        @if (isset($workout['adapter_array']['time_under_tension'][$i]))
                            @if($workout['adapter_array']['time_under_tension'][$i]> 1)
                                <div class="col-span-2">
                                    <span class="text-xs text-gray-400">TEMPO: </span>
                                    {{$workout['adapter_array']['time_under_tension'][$i]}}
                                </div>
                            @endif
                        @endif
                        @if (isset($workout['adapter_array']['one_rep_max'][$i]))
                            <div class="col-span-2">
                                <span class="text-xs text-gray-400">1RM: </span>
                                {{$workout['adapter_array']['one_rep_max'][$i]}}%
                                @if (!isset($adapterData[$i]))

                                @else
                                    <span class="text-xs text-gray-500">({{$adapterData[$i]}}kg)</span>
                                @endif
                            </div>
                        @endif
                        @if (isset($workout['adapter_array']['one_rep_max'][$i]))
                            @if (!isset($adapterData[$i]))
                                <div class="col-span-{{$counter*2}}">
                                <span class="text-xs bg-red-100 border border-red-400 text-red-700 rounded "
                                      role="alert">Missing 1RM set. <a
                                        href="https://blog.dingbat.app/index.php/2021/06/29/warning-missing-1rm-set/">More info.</a></span>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            @endif


        </div>
        <br/>
    @endfor
</div>
</div>
