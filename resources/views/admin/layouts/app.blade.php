<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Locale -->
    <meta name="locale" content="{{ app()->getLocale() }}">

    <title>{{ config('app.name') }} Administrator System</title>

    <link rel="icon" type="image/png" href="{{asset('images/admin/favicon.png')}}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link href="{{ asset('css/vendor/admin/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/admin/material-dashboard.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/admin/element-ui.css') }}" rel="stylesheet">
    <link href="{{ mix('css/admin_app.css') }}" rel="stylesheet">

</head>
    <body>
    @include('admin.layouts.preloader')
    <div class="wrapper" id="app">
        <side-bar
            :user="{{ json_encode($_user) }}"
            :system-role-id="{{ config('app.system_role_id')  }}"
            :active-route="{{ json_encode($_activeRoute) }}"
        >
        </side-bar>
        <div class="main-panel">
            <nav-bar
                :breadcrumb="{{ json_encode($_breadcrumb) }}"
                :notifications="{{ json_encode($_notifications) }}"
            ></nav-bar>
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Core JavaScript -->
    <script src="{{ asset('js/vendor/admin/jquery.min.js') }}"></script>

    <!-- Material dashboard require -->
    <script src="{{ asset('js/vendor/admin/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/vendor/admin/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/vendor/admin/material-kit.min.js') }}"></script>
    <script src="{{ asset('js/vendor/admin/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/admin/material-dashboard.min.js') }}"></script>

    <!-- Editor -->
    <script src="{{ asset('js/vendor/admin/ckeditor/ckeditor.js') }}"></script>

    <!-- App -->
    <script src="{{ mix('js/admin/admin_app.js') }}" defer></script>
    </body>
</html>
