<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title', 'Energy Inspection Services Ltd')</title>

    <meta name="description" content="@yield('meta_description', 'Inspection services for the Oil and Gas Industries')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts (optional, remove if not used) -->
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com"> --}}

 <style>
html, body{
  max-width:100%;
  margin:0;
  padding:0;
  overflow-x:hidden;
}
*, *::before, *::after{
  box-sizing:border-box;
}
</style>

    <!-- Styles -->
    @stack('styles')

</head>
<body>

    {{-- Page content --}}
    @yield('content')

    <!-- Scripts -->
    @stack('scripts')

</body>
</html>