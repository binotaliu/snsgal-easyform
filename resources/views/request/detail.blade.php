@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('request.detail_title') }}
                </div>

                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>{{ trans('request.field_title') }}</td>
                                <td>{{ $request->title }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_token') }}</td>
                                <td>
                                    <a href="{{ url("request/{$request->token}") }}" target="_blank">
                                        {{ $request->token }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_description') }}</td>
                                <td>{!! nl2br(htmlspecialchars($request->description)) !!}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_shipping') }}</td>
                                @if ($request->address_type == 'cvs')
                                    <td>{{ trans('request.shipping_cvs') }}</td>
                                @elseif ($request->address_type == 'standard')
                                    <td>{{ trans('request.shipping_standard') }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_responded?') }}</td>
                                <td>{{ $request->responded ? trans('request.status_yes') : trans('request.status_no') }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_exported?') }}</td>
                                <td>{{ $request->exported ? trans('request.status_yes') : trans('request.status_no') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($request->responded)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Address
                    </div>

                    <div class="panel-body">
                        <address class="well well-lg">
                            {{ $request->address->receiver }} {{ $request->address->phone }} <br>
                            @if ($request->address_type == 'standard')
                                {{ $request->address->postcode }} {{ $request->address->county . $request->address->city }} <br>
                                {{ $request->address->address1 }} <br>
                                {{ $request->address->address2 }}
                            @elseif ($request->address_type == 'cvs')
                                {{ $request->address->vendor }} {{ $request->address->store }}
                            @endif
                        </address>

                        @if ($request->exported)
                            ECPayID: {{ $request->exported }}
                        @else
                            <form class="form-horizontal" action="{{ url("/request/{$request->token}/export") }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="export-product-name" class="col-sm-2 control-label">{{ trans('request.field_product_name') }}</label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control" id="export-product-name" name="product_name" maxlength="60" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-amount" class="col-sm-2 control-label">{{ trans('request.field_product_amount') }}</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <div class="input-group-addon">NT$</div>
                                            <input type="number" class="form-control" id="export-amount" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-sender" class="col-sm-2 control-label">{{ trans('request.field_sender_name') }}</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="export-sender" name="sender" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-sender-phone" class="col-sm-2 control-label">{{ trans('request.field_sender_phone') }}</label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="export-sender-phone" name="sender_phone" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-collect" class="col-sm-2 control-label">{{ trans('request.field_collect?') }}</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="export-collect" name="collect">
                                            <option value="N">{{ trans('request.status_no') }}</option>
                                            <option value="Y">{{ trans('request.status_yes') }}</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($request->address_type == 'standard')
                                    <div class="form-group">
                                        <label for="export-vendor" class="col-sm-2 control-label">{{ trans('request.field_vendor') }}</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-vendor" name="vendor">
                                                <option value="TCAT">{{ trans('request.vendor_tcat') }}</option>
                                                <option value="ECAN">{{ trans('request.vendor_ecan') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-sender-postcode" class="col-sm-2 control-label">{{ trans('request.field_sender_postcode') }}</label>
                                        <div class="col-sm-5">
                                            <input type="number" class="form-control" id="export-sender-postcode" name="sender_postcode" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-sender-address" class="col-sm-2 control-label">{{ trans('request.field_sender_address') }}</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="export-sender-address" name="sender_address" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-temperature" class="col-sm-2 control-label">{{ trans('request.field_temperature') }}</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-temperature" name="temperature">
                                                <option value="0001">{{ trans('request.temperature_normal') }}</option>
                                                <option value="0002">{{ trans('request.temperature_refrigeration') }}</option>
                                                <option value="0003">{{ trans('request.temperature_freezing') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-distance" class="col-sm-2 control-label">{{ trans('request.field_distance') }}</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-distance" name="distance">
                                                <option value="00">{{ trans('request.distance_local') }}</option>
                                                <option value="01">{{ trans('request.distance_difference_county') }}</option>
                                                <option value="02">{{ trans('request.distance_outer_island') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-specification" class="col-sm-2 control-label">{{ trans('request.field_specification') }}</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-specification" name="specification">
                                                <option value="0001">60cm</option>
                                                <option value="0002">90cm</option>
                                                <option value="0003">120cm</option>
                                                <option value="0004">150cm</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="col-sm-5 col-sm-offset-2">
                                        <button type="submit" class="btn btn-md btn-primary">{{ trans('request.export_btn') }}</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
