@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.profile_title') }}
                    </div>

                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ url('/request/profile') }}" method="POST" class="form-horizontal">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="profile-name" class="col-sm-3 control-label">{{ trans('request.field_sender_name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="profile-name" name="name" value="{{ old('name') ? old('name') : $requestProfile->name }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profile-phone" class="col-sm-3 control-label">{{ trans('request.field_sender_phone') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="profile-phone" name="phone" value="{{ old('phone') ? old('phone') : $requestProfile->phone }}" required>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label for="profile-postcode" class="col-sm-3 control-label">{{ trans('request.field_sender_postcode') }}</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="profile-postcode" name="postcode" max="999" value="{{ old('postcode') ? old('postcode') : $requestProfile->postcode }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="profile-address" class="col-sm-3 control-label">{{ trans('request.field_sender_address') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="profile-address" name="address" value="{{ old('address') ? old('address') : $requestProfile->address }}" required>
                                    </div>
                                </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="submit" class="btn btn-md btn-primary">{{ trans('request.profile_btn') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
