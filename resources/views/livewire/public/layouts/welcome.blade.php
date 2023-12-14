<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dingbat') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet"/>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('head')
    @livewireStyles
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    @include('scripts.header-scripts')

    <style>
        .gradient {
            background: linear-gradient(90deg, #d53369 0%, #daae51 100%);
        }
    </style>


</head>
<body class="leading-normal tracking-normal text-white gradient" style="font-family: 'Source Sans Pro', sans-serif;">

<!--Main-->
<main>
    {{ $slot }}
</main>

</div>

@stack('modals')
@livewireScripts
</body>
</html>
