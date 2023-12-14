<div>
    <div class="text-center text-2xl">
        {{$plan->plan_name}}
    </div>
    @if (isset($dailyVolume) && isset($dailySlotVolume) && isset($colorPalette))
        @include('partials.chart-dailyvolume',
            ['dailyVolume'=>$dailyVolume,
            'dailySlotVolume'=>$dailySlotVolume,
            'colorPalette' => $colorPalette])
    @endif
    @if ($activePlan->plan == $plan->plan_id)
        <br/>
        <strong>Active plan - started {{$planStartDateStr}}. </strong><br/>
    @endif
    <br/>
    @foreach ($weeks as $week)
        <a href="/plan/{{$plan->plan_id}}/week/view/{{$week->week_id}}">
            <div class="border-b-4 w-auto border-indigo-400 text-xl">
                Week {{($loop->index + 1)}} - <span class="text-sm text-gray-400">{{$weekStartAndEndStr[$week->week_id]['start']}} to  {{$weekStartAndEndStr[$week->week_id]['end']}} </span>
            </div>
        </a>
        @if (!isset($weekStats[$week->week_id]))
            This week has not been started
        @else
            <div class="w-auto">
                <div class="flex flex-row pr-8 border-b-2 w-auto border-grey-200">
                    <div class="flex-grow w-6 content-start">Completed sets:</div>
                    <div>{{ number_format($weekStats[$week->week_id]['sets'])}}</div>
                </div>
                <div class="flex flex-row pr-8 border-b-2 w-auto border-grey-200">
                    <div class="flex-grow w-12">Completed reps:</div>
                    <div>{{ number_format($weekStats[$week->week_id]['reps'])}}</div>
                </div>
                <div class="flex flex-row pr-8 border-b-2 w-auto border-grey-200">
                    <div class="flex-grow w-12">Total Volume:</div>
                    <div> {{ number_format($weekStats[$week->week_id]['volume'])}}</div>
                </div>
                <div class="flex flex-row pr-8 border-b-2 w-auto border-grey-200">
                    <div class="flex-grow w-12">Compliance:</div>
                    @if(isset($compliance[$week->week_id]))

                        <div>
                            % {{ number_format(((100/$targetCompliance[$week->week_id])*$compliance[$week->week_id]),2) }}
                        </div>
                    @else
                        <div>% NIL</div>
                    @endif

                </div>
                <div class="w-auto">
                    @foreach ($slotVolume[$week->week_id] as $key => $slot)
                        <div class="flex flex-row pr-8 border-b-2 w-auto border-grey-200">
                            <div class="flex-grow w-12">Slot Volume {{chr(64 + $key + 1)}}:</div>
                            <div>{{ number_format($slot)}}</div>
                        </div>
                    @endforeach

                    @endif
                    <br/>
                    @endforeach
                </div>
            </div>
</div>
