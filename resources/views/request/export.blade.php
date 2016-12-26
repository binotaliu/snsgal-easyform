@extends('layouts.simple')

@section('content')
    @if (env('ECPAY_MERCHANTID') == '2000132' || env('ECPAY_MERCHANTID') == '2000933')
        <form id="exporter" action="https://logistics-stage.ecpay.com.tw/express/create" method="POST" class="display: none;">
    @else
        <form id="exporter" action="https://logistics.ecpay.com.tw/express/create" method="POST">
    @endif
            @foreach ($data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
@endsection

@section('footer')
    <script>
        (function () {
            $('#exporter').submit();
        })();
    </script>
@endsection
