@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Request Detail
                </div>

                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Title</td>
                                <td>{{ $request->title }}</td>
                            </tr>
                            <tr>
                                <td>Token</td>
                                <td>{{ $request->token }}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>{!! nl2br(htmlspecialchars($request->description)) !!}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ $request->address_type }}</td>
                            </tr>
                            <tr>
                                <td>Responded?</td>
                                <td>{{ $request->responded ? 'yes' : 'no' }}</td>
                            </tr>
                            <tr>
                                <td>Exported?</td>
                                <td>{{ $request->exported ? 'yes' : 'no' }}</td>
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
                                    <label for="export-product-name" class="col-sm-2 control-label">Product Name</label>
                                    <div class="col-sm-5">
                                        <textarea type="text" class="form-control" id="export-product-name" name="product_name" maxlength="60" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-amount" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <div class="input-group-addon">NT$</div>
                                            <input type="number" class="form-control" id="export-amount" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-sender" class="col-sm-2 control-label">Sender Name</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="export-sender" name="sender" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-sender-phone" class="col-sm-2 control-label">Sender Phone</label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="export-sender-phone" name="sender_phone" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="export-collect" class="col-sm-2 control-label">Collect Amount?</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="export-collect" name="collect">
                                            <option value="N">No</option>
                                            <option value="Y">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($request->address_type == 'standard')
                                    <div class="form-group">
                                        <label for="export-vendor" class="col-sm-2 control-label">Collect Amount?</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-vendor" name="vendor">
                                                <option value="TCAT">TCat</option>
                                                <option value="ECAN">ECan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-sender-postcode" class="col-sm-2 control-label">Sender Post Code</label>
                                        <div class="col-sm-5">
                                            <input type="number" class="form-control" id="export-sender-postcode" name="sender_postcode" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-sender-address" class="col-sm-2 control-label">Sender Address</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="export-sender-address" name="sender_address" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-temperature" class="col-sm-2 control-label">Temperature</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-temperature" name="temperature">
                                                <option value="0001">Normal</option>
                                                <option value="0002">Refrigeration</option>
                                                <option value="0003">Freezing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-distance" class="col-sm-2 control-label">Distance</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" id="export-distance" name="distance">
                                                <option value="00">Local</option>
                                                <option value="01">Different County</option>
                                                <option value="02">Outer Island</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="export-specification" class="col-sm-2 control-label">Specification</label>
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
                                        <button type="submit" class="btn btn-md btn-primary">Export to ECPay</button>
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
