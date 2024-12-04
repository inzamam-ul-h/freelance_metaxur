


<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.header_links')
    {{--
    <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <link rel="icon" href="{{ url('/assets/img/metaxure.png') }}" type="image/x-icon">
</head>

<body class="index-page" >

    @include('layouts.header')
    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer_links')
    @yield('scripts')
</body>

</html>
