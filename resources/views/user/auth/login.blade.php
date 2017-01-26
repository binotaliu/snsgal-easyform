@extends('layouts.app')

@section('title', trans('auth.title_login'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div id="auth0-root">
                Loading...
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script>
        (function () {
            "use strict";
            var lock = new Auth0Lock(AUTH0_CLIENT_ID, AUTH0_DOMAIN, extend({container: 'auth0-root'}, auth0Configurations));
            lock.show();
        })();
    </script>
@endsection
