<!DOCTYPE html>
<html lang="zh-hant">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', '穹ノ空') }}</title>

    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    <script>
        window.Snsgal = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', '穹ノ空') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if (!Auth::guest() && Auth::user()->is_admin)
                            <li><a href="{{ url('/shipment/requests') }}">{{ trans('request.list_title') }}</a></li>
                            <li><a href="{{ url('/procurement/tickets') }}">{{ trans('procurement_ticket.ticket') }}</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="javascript:void;" onclick="lock.show();">{{ trans('auth.link_login_register') }}</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/user/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ trans('auth.link_logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ url('/user/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

    </div>


    <footer class="container">
        <div class="row">
            <div class="col-sm-12">
                <p class="active">{{ date('Y') }} &copy; snsgal.com, all rights reserved.</p>
            </div>
        </div>
    </footer>
    {{-- Scripts --}}
    <script src="{{ elixir('js/app.js') }}"></script>
    <script src="https://cdn.auth0.com/js/lock/10.9.1/lock.min.js"></script>
    <script>
        "use strict";
        var AUTH0_CLIENT_ID = '{{ env('AUTH0_CLIENT_ID') }}';
        var AUTH0_DOMAIN = '{{ env('AUTH0_DOMAIN') }}';
        var auth0Configurations = {
            auth: {
                redirectUrl: '{{ url('auth0/callback') }}',
                responseMode: 'form_post',
                responseType: 'code',
                params: {
                    scope: 'openid email'
                }
            },
            language: 'zh-TW',
        };
        var lock = new Auth0Lock(AUTH0_CLIENT_ID, AUTH0_DOMAIN, auth0Configurations);
    </script>
    @yield('footer')
</body>
</html>
