@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.response_title') }}
                    </div>

                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="well well-sm">
                            {!! nl2br(htmlspecialchars($request->description)) !!}
                        </div>

                        <form action="{{ url("/shipment/request/{$request->token}/address") }}" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="address-receiver" class="col-sm-2 control-label">{{ trans('request.field_receiver') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="address-receiver" name="receiver" value="{{ old('receiver') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address-phone" class="col-sm-2 control-label">{{ trans('request.field_phone') }}</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="address-phone" name="phone" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            @if ($request->address_type == 'standard')
                                <div class="form-group">
                                    <label for="address-city" class="col-sm-2 control-label">{{ trans('request.field_city') }}</label>
                                    <div class="col-sm-10">
                                        <zipcode></zipcode>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address-address1" class="col-sm-2 control-label">{{ trans('request.field_address1') }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address-address1" name="address1" value="{{ old('address1') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address-address2" class="col-sm-2 control-label">{{ trans('request.field_address2') }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address-address2" name="address2" value="{{ old('address2') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address-time" class="col-sm-2 control-label">{{ trans('request.field_delivery_time') }}</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="address-time" name="time">
                                            @if ( old('time') == 0 )
                                                <option value="0" selected>{{ trans('request.time_unlimited') }}</option>
                                            @else
                                                <option value="0">{{ trans('request.time_unlimited') }}</option>
                                            @endif

                                            @if ( old('time') == 1 )
                                                <option value="1" selected>9 ~ 12</option>
                                            @else
                                                <option value="1">9 ~ 12</option>
                                            @endif

                                            @if ( old('time') == 2 )
                                                <option value="2" selected>12 ~ 17</option>
                                            @else
                                                <option value="2">12 ~ 17</option>
                                            @endif

                                            @if ( old('time') == 3 )
                                                <option value="3" selected>17 ~ 20</option>
                                            @else
                                                <option value="3">17 ~ 20</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @elseif ($request->address_type == 'cvs')
                                <div class="form-group">
                                    <label for="request-store" class="col-sm-2 control-label">{{ trans('request.field_cvs') }}</label>
                                    <div class="col-sm-7">
                                        <input type="hidden" id="request-vendor" name="vendor" value="{{ old('vendor') }}">
                                        <input type="hidden" id="request-store" name="store" value="{{ old('store') }}">
                                        <input type="text" id="request-store-showname" name="store-showname" class="form-control" value="{{ old('store-showname') }}" disabled required>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" id="show-map" class="btn btn-block btn-default">{{ trans('request.map_btn') }}</button>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <input type="hidden" name="address_type" value="{{ $request->address_type }}">
                                    <button type="submit" class="btn btn-default">{{ trans('request.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ elixir('js/requests.js') }}"></script>
    <script>
        (function() {
            "use strict";
            $('#show-map').click(function () {
                let mapWindow = window.open('{{ url('map/cvs') }}', 'cvsMap', 'width=1000,height=600,toolbar=0,menubar=0,location=0');
                if (window.focus) mapWindow.focus();

                window.setStore = function (vendor, id, name) {
                    $('#request-vendor').val(vendor);
                    $('#request-store').val(id);
                    $('#request-store-showname').val(vendor + ' - ' + id + ' ' + name);
                };
            });
        })();
    </script>
@endsection