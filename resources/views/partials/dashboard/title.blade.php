<!-- Title -->
<div class="text-center text-2xl">
@if($daysIn)
    Day {{$daysIn}}
@endif
@if (isset($planName) && isset($planId))
    -
    <a href="/plan/view/{{$planId}}">
        {{$planName}}
    </a>
@endif
    <div class="text-center">
    @if (isset($curDay))
        <span class="text-xs text-center text-gray-500">{{$curDay->format('jS \o\f M, Y')}}</span>
    @endif
    </div>
</div>
