@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.list_title') }}
                    </div>

                    <div class="panel-body">
                        {{ $requests->links() }}
                        <div class="pull-right">
                            <a href="{{ url('/request/create') }}" class="btn btn-success btn">{{ trans('request.create_btn') }}</a>
                        </div>

                        <table class="table table-bordered">
                            <thead><tr>
                                <td>#</td>
                                <td>{{ trans('request.field_title') }}</td>
                                <td>{{ trans('request.field_token') }}</td>
                                <td>{{ trans('request.field_type') }}</td>
                                <td>{{ trans('request.field_responded?') }}</td>
                                <td>{{ trans('request.field_exported?') }}</td>
                                <td>{{ trans('request.field_actions') }}</td>
                            </tr></thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->title }}<br>
                                        <td><a href="{{ url("/request/{$request->token}") }}" target="_blank">{{ $request->token }}</a></td>
                                        @if ($request->address_type == 'cvs')
                                            <td>{{ trans('request.type_cvs') }}</td>
                                        @elseif ($request->address_type == 'standard')
                                            <td>{{ trans('request.type_standard') }}</td>
                                        @endif
                                        <td>{{ $request->responded ? trans('request.status_yes') : trans('request.status_no') }}</td>
                                        <td>{{ $request->exported ? trans('request.status_yes') : trans('request.status_no') }}</td>
                                        <td>
                                            <a href="{{ url("/request/{$request->token}/detail") }}" class="btn btn-primary" target="_blank">{{ trans('request.detail_btn') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $requests->links() }}
                        <div class="pull-right">
                            <a href="{{ url('/request/create') }}" class="btn btn-success btn">{{ trans('request.create_btn') }}</a>
                        </div>
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel -->
            </div> <!-- /.col-sm-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
@endsection
