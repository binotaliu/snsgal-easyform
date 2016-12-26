@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.create_title') }}
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

                        <form action="{{ url('/request/create') }}" method="POST" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="request-title" class="col-sm-2 control-label">{{ trans('request.field_title') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="request-title" name="title" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="request-description" class="col-sm-2 control-label">{{ trans('request.field_description') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="request-description" name="description" rows="8">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="request-type" class="col-sm-2 control-label">{{ trans('request.field_shipping') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="request-type" name="type">
                                        @if (old('type') == 'standard')
                                            <option value="standard">{{ trans('request.shipping_standard') }}</option>
                                        @else
                                            <option value="standard" selected>{{ trans('request.shipping_standard') }}</option>
                                        @endif

                                        @if (old('type') == 'cvs')
                                            <option value="cvs" selected>{{ trans('request.shipping_cvs') }}</option>
                                        @else
                                            <option value="cvs">{{ trans('request.shipping_cvs') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
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
