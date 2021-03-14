<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
</head>
<body>
<form id="auto-form" action="{{ $url }}" method="{{ $method }}">
    @foreach ($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <noscript>
        <button type="submit">Continue 繼續</button>
    </noscript>
</form>
<script>(function () { document.getElementById('auto-form').submit() })()</script>
</body>
</html>
