<div class="text-center text-2xl">
    @if (isset($date) && isset($planId))
        <a href="/plan/view/{{$planId}}">
            {{$date->format('d F Y')}}
        </a>
    @elseif (isset($planName))
        {{$planName}}
    @endif
    <br/>
    <br/>{!! $error !!} <br/><br/>😭😭😭😭😭😭😭
</div>
