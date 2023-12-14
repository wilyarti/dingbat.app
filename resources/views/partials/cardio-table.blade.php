@if ($circuits)
    @for ($i = 0; $i < sizeof($circuits); $i++)
        <div class="border-b-4 w-auto border-pink-400">
            <strong>Circuit x2: {{$circuits[$i]->circuit_name}}</strong>
        </div>
        @foreach($circuits[$i]->exercise_list as $line)
            <div class="gap-4 flex flex-row-reverse border-b-2 w-auto border-indigo-400">
                <span class="text-xs text-gray-400">30 sec</span>
                <div>{{$line}}</div>
            </div>
        @endforeach
    @endfor
@endif
