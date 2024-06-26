<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Yubin Terminator') }}</title>

    <!-- Styles -->
    <link href="{{ mix('dist/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Snsgal = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('dist/js/app.js') }}"></script>
    @yield('footer')
</body>
</html>
