<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ env('APP_NAME') }}</title>
    {{-- <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-vue/2.18.1/bootstrap-vue.min.css" />

    <script src="//cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-vue/2.18.1/bootstrap-vue.min.js"></script>
    <style>
        .toast:not(.show) {
            display: block;
        }
    </style>
</head>

<body>
    @if (Auth::check())
        <script>
            window.Laravel = {!! json_encode([
                'isLoggedin' => true,
                'user' => Auth::user(),
            ]) !!}
        </script>
    @else
        <script>
            window.Laravel = {!! json_encode([
                'isLoggedin' => false,
            ]) !!}
        </script>
    @endif
    <div id="app">
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ mix('js/app.js') }}" type="text/javascript"></script> --}}
</body>

</html>
