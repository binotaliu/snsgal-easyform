@extends('layouts.app')

@section('title', trans('request.response_title'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.response_title') }}
                    </div>

                    <div class="panel-body">
                        <div class="alert alert-info">
                            <p>{!! nl2br(htmlspecialchars($request->description)) !!}</p>
                        </div>

                        @if ($request->exported)
                            <p><strong>{{ trans('request.field_shipment_ticket_id') }}</strong>: {{ $request->shipment_ticket_id }}</p>
                            <p><strong>{{ trans('request.field_shipping_status') }}</strong>: {{ $ecpay_status['description']}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
