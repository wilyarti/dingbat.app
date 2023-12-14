<div>
    <div class="w-auto">
        @foreach ($exerciseStats as $value)
            <div class="text-2xl">
                <b></b> {{$value['muscle_name']}}
            </div>
            @foreach ($value['exercise'] as $exerciseName => $exercise)
                <a href="/exercise/view/{{$exercise['exercise_id']}}">
                    <div class="border-b-4 w-auto border-indigo-400">
                        {{$exerciseName}}<br/>
                    </div>
                </a>
                <div class="">
                    <div class="grid grid-cols-4 gap-4 ">
                        <div class="col-start-6 col-span-2">
                            <span class="text-xs text-gray-400">SET COUNT: </span>
                        </div>
                        <div class="col-start-10 col-span-2">
                            {{$exercise['setCount']}}
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4 ">
                        <div class="col-start-6 col-span-2">
                            <span class="text-xs text-gray-400">TOTAL REPS:</span>
                        </div>
                        <div class="col-start-10 col-span-2">
                            {{$exercise['reps']}}
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4 ">
                        <div class="col-start-6 col-span-2">
                            <span class="text-xs text-gray-400">VOLUME: </span>
                        </div>
                        <div class="col-start-10 col-span-2">
                            {{$exercise['volume']}} kgs
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="border-b-4 w-auto border-pink-400">
                Totals:
                <br/>
            </div>
            <div class="">
                <div class="grid grid-cols-4 gap-4 ">
                    <div class="col-start-6 col-span-2">
                        <span class="text-xs text-gray-400">SET COUNT: </span>
                    </div>
                    <div class="col-start-10 col-span-2">
                        {{$value['setCount']}}
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 ">
                    <div class="col-start-6 col-span-2">
                        <span class="text-xs text-gray-400">TOTAL REPS:</span>
                    </div>
                    <div class="col-start-10 col-span-2">
                        {{$value['reps']}}
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 ">
                    <div class="col-start-6 col-span-2">
                        <span class="text-xs text-gray-400">VOLUME: </span>
                    </div>
                    <div class="col-start-10 col-span-2">
                        {{$value['totalVolume']}} kgs
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>
