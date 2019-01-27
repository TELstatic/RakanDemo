<!--
        ___               __         _ __
       /   |  _________ _/ /_  ___  (_) /___ _
      / /| | / ___/ __ `/ __ \/ _ \/ / / __ `/
     / ___ |/ /  / /_/ / /_/ /  __/ / / /_/ /
    /_/  |_/_/   \__,_/_.___/\___/_/_/\__,_/
  ------------------------------------------------
                          {{'//'.config('app.url')}}

        - PHP:  {{PHP_VERSION}}
        - Laravel: {{app()->version()}}
        -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{config('app.name')}}</title>
    @if(config('app.env')=='local')
        <link rel="stylesheet" href="/vendor/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="/vendor/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/vendor/css/ionicons.min.css"/>
        <link rel="stylesheet" href="/vendor/css/AdminLTE.min.css"/>
        <link rel="stylesheet" href="/vendor/css/_all-skins.min.css"/>

        <link rel="stylesheet" href="/vendor/css/iview.css"/>
        <link rel="stylesheet" href="/vendor/css/element-chalk.css"/>
        <link rel="stylesheet" href="/vendor/css/quill.snow.css"/>
    @else
        <link rel="stylesheet" href="/vendor/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="/vendor/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/vendor/css/ionicons.min.css"/>
        <link rel="stylesheet" href="/vendor/css/AdminLTE.min.css"/>
        <link rel="stylesheet" href="/vendor/css/_all-skins.min.css"/>

        <link rel="stylesheet" href="/vendor/css/iview.css"/>
        <link rel="stylesheet" href="/vendor/css/element-chalk.css"/>
        <link rel="stylesheet" href="/vendor/css/quill.snow.css"/>
    @endif
</head>
<body class="hold-transition skin-purple sidebar-mini" style="background: #e8e8e8">
<div class="wrapper">
    @include('layouts.Admin.header')
    @include('layouts.Admin.sidebar')
    @yield('content')
    @include('layouts.Admin.footer')
</div>
</body>
</html>