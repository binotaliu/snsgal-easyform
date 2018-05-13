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
          const AUTH0_CLIENT_ID = '{{ env('AUTH0_CLIENT_ID') }}';
          const AUTH0_DOMAIN = '{{ env('AUTH0_DOMAIN') }}';
          const auth0Configurations = {
            auth: {
              redirectUrl: '{{ url('auth0/callback') }}',
              responseMode: 'query',
              responseType: 'code',
              params: {
                scope: 'openid email',
              },
            },
            language: 'zh-TW',
          };
          const lock = new Auth0Lock(AUTH0_CLIENT_ID, AUTH0_DOMAIN, extend({container: 'auth0-root'}, auth0Configurations));
          lock.show();
        })();
    </script>
@endsection
