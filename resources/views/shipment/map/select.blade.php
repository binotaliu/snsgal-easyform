@extends('layouts.simple')

@section('request.map_title')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1></h1>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.map_title') }}
                    </div>

                    <div class="panel-body text-center">
                        <div class="btn-group" id="map-selector">
                            <button type="button" data-cvs="UNIMART" class="btn btn-lg btn-default">{{ trans('request.cvs_unimart') }}</button>
                            <button type="button" data-cvs="FAMI" class="btn btn-lg btn-default">{{ trans('request.cvs_fami') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (env('ECPAY_MERCHANTID') == '2000132' || env('ECPAY_MERCHANTID') == '2000933')
        <form id="map-opener" action="https://logistics-stage.ecpay.com.tw/express/map" method="POST" class="display: none;">
    @else
        <form id="map-opener" action="https://logistics.ecpay.com.tw/express/map" method="POST" class="display: none;">
    @endif
            <input type="hidden" name="MerchantID" value="{{ env('ECPAY_MERCHANTID') }}">
            <input type="hidden" name="MerchantTradeNo" value="{{ time() . rand(10000, 99999) }}">
            <input type="hidden" name="LogisticsType" value="CVS">
            <input type="hidden" name="LogisticsSubType" value="">
            <input type="hidden" name="IsCollection" value="N">
            <input type="hidden" name="ServerReplyURL" value="{{ url('map/cvs/response') }}">
            <input type="hidden" name="ExtraData" value="">
            <input type="hidden" name="Device" value="0">
        </form>
@endsection

@section('footer')
    <script>
        (function() {
            "use strict";

            $('#map-selector').find('button').click(function () {
                $('#map-opener').find('input[name="LogisticsSubType"]').val($(this).attr('data-cvs'));
                $('#map-opener').submit();
            });
        })();
    </script>
@endsection
