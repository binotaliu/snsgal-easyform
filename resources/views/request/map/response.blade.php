@extends('layouts.simple')

@section('footer')
    <script>
        (function () {
            window.opener.setStore('{{ $vendor }}', '{{ $store }}', '{{ $name }}');
            window.close();
        })();
    </script>
@endsection
