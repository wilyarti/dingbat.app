<div>
    @foreach ($keyedValues as $key => $measurements)
        @for  ($i =0; $i < sizeof($measurements); $i++)
            @if ($i ==0)
                <div class="border-b-4 w-auto border-indigo-400">
                    {{$map[$key]}}</div>
            @endif
            <div class="w-auto">
                <div

                    @if ($editingEnabled)
                    wire:click="edit({{$measurements[$i]['body_measurement_id']}})"
                    @endif
                    class="flex  gap-4 flex-row-reverse pr-8">
                    @if ($editEntry == $measurements[$i]['body_measurement_id'])
                        <a
                            @if (isset($measurements[$i]['jp3']) ||isset($measurements[$i]['jp7']) || isset($measurements[$i]['parillo']) || isset($measurements[$i]['durnin']) )
                            href="/skinfold/{{$measurements[$i]['body_measurement_id']}}"
                            @else
                            href="/track/{{$measurements[$i]['body_measurement_id']}}"
                            @endif
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path
                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                <path fill-rule="evenodd"
                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endif
                    <div>{{$measurements[$i][$key]}}</div>
                    <div>
                        <span
                            class="text-xs text-gray-400">{{date_format(new DateTime($measurements[$i]['date']), 'd M Y')}}</span>
                    </div>
                    @if ($editEntry == $measurements[$i]['body_measurement_id'])
                        <div wire:click="delete({{$measurements[$i]['body_measurement_id']}})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                @endfor
                @endforeach
            </div>
</div>
