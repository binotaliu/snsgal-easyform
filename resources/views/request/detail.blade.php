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
                        <address>
                            {{ $request->address->receiver }} {{ $request->address->phone }} <br>
                            @if ($request->address_type == 'standard')
                                {{ $request->address->postcode }} {{ $request->address->county . $request->address->city }} <br>
                                {{ $request->address->address1 }} <br>
                                {{ $request->address->address2 }}
                            @elseif ($request->address_type == 'cvs')
                                {{ $request->address->vendor }} {{ $request->address->store }}
                            @endif
                        </address>

                        @if ($request->address_exported)
                            ECPayID: {{ $request->exported }}
                        @else
                            <a href="{{ url("/request/{$request->token}/export") }}" class="btn btn-md btn-primary">Export to ECPay</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
