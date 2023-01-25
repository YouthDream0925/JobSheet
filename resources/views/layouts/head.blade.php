<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 admin dashboard template & web App ui kit.">
    <meta name="keyword" content="QBoat, Bootstrap 5, Admin Dashboard, Admin Theme">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> <!--[ Favicon]-->
    <title>{{ __('global.appTitle') }}</title>

    <!--[ plugin css file  ]-->
    <link rel="stylesheet" href="{{asset('dist/bundles/bootstrapdatepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/custom.css')}}">
    @stack('css')
    <!--[ project css file  ]-->
    <link rel="stylesheet" href="{{asset('dist/css/style.css')}}">
    
    <!--[ Jquery Core Js ]-->
    <script src="{{asset('dist/js/plugins.js')}}"></script>
</head>