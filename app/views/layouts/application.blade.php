@extends('layouts.master')

@section('body')

<header class="navbar navbar-static-top">
    <nav class="navbar-inner">
        <div class="container">
            <a class="brand" href="{{ url('/') }}">the drop.</a>

            <ul class="nav pull-right visible-phone">
                <li><a href="{{ URL::route('logout') }}"><i class="icon-signout"></i></a></li>
            </ul>

            <ul class="nav pull-right hidden-phone">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="current-page">@yield('pageName')</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li{{ Route::is('files') ? ' class="active"' : '' }}><a href="{{ URL::route('files') }}">Files <span class="badge{{ Session::get('undownloaded.files.count', 0) > 0 ? ' badge-warning' : '' }}">{{{ Session::get('undownloaded.files.count', 0) }}}</span></a></li>
                        <li{{ Route::is('schedule') ? ' class="active"' : '' }}>{{ link_to_route('schedule', 'Schedule') }}</li>
                        <li{{ Route::is('shows') ? ' class="active"' : '' }}>{{ link_to_route('shows', 'List of Shows') }}</li>
                        <li{{ Route::is('donate') ? ' class="active"' : '' }}>{{ link_to_route('donate', 'Donate') }}</li>
                        <li{{ Route::is('chromeapp') ? ' class="active"' : '' }}>{{ link_to_route('chromeapp', 'Chrome Extension') }}</li>
                        <li class="divider"></li>
                        <li{{ Request::is('my/requests', 'my/requests/*') ? ' class="active"' : '' }}>{{ link_to_action('FileRequestController@index', 'My Requests') }}</li>
                        <li{{ Request::is('my/downloads/*') ? ' class="active"' : '' }}>{{ link_to_action('DownloadsController@getIndex', 'My Downloads') }}</li>
                        <li{{ Request::is('my/invites') ? ' class="active"' : '' }}>{{ link_to_action('InvitesController@getIndex', 'My Invites') }}</li>
                        <li{{ Request::is('my/profile') ? ' class="active"' : '' }}>{{ link_to_action('ProfileController@getIndex', 'My Profile') }}</li>
                        <li{{ Request::is('my/settings/*') ? ' class="active"' : '' }}>{{ link_to_action('MySettingsController@getIndex', 'My Settings') }}</li>
                        <li class="divider"></li>
                        @if ( Sentry::getUser()->isAdmin() )
                        <li{{ Request::is('manage/file', 'manage/file/*') ? ' class="active"' : '' }}>{{ link_to_action('AdminFileController@index', 'Manage Files') }}</li>
                        <li{{ Request::is('manage/request', 'manage/request/*') ? ' class="active"' : '' }}><a href="{{ URL::action('AdminFileRequestController@index') }}">Manage Requests <span class="badge{{ Session::get('open.request.count', 0) > 0 ? ' badge-important' : '' }}">{{{ Session::get('open.request.count', 0) }}}</span></a></li>
                        <li{{ Request::is('manage/invite', 'manage/invite/*') ? ' class="active"' : '' }}>{{ link_to_action('AdminInviteController@index', 'Manage Invites') }}</li>
                        <li{{ Request::is('manage/user', 'manage/user/*') ? ' class="active"' : '' }}>{{ link_to_action('AdminUserController@index', 'Manage Users') }}</li>
                        <li{{ Request::is('manage/statistics*') ? ' class="active"' : '' }}>{{ link_to_action('AdminDownloadStatisticsController@getIndex', 'Download Statistics') }}</li>
                        <li class="divider"></li>
                        @endif
                        <li>{{ link_to_route('logout', 'Sign Out') }}</li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div id="message-container">
    @if (Session::has('actionFlash.message'))
    <div class="container">
        <div class="alert alert-block {{ 'alert-' . Session::get('actionFlash.status', 'error') }}">
            {{ Session::get('actionFlash.message') }}
        </div>
    </div>
    @endif
</div>

@yield('content')

<footer class="container">
    <p class="muted text-center"><i class="icon-beer" title="Repositronic Mega 5000 (BMO Edition)"></i></p>
    @if (Sentry::getUser()->isAdmin())
    <p class="muted text-center">Current Environment: <code>{{{ App::environment() }}}</code></p>
    @endif
</footer>

@if(Sentry::getUser()->getSetting('background.enable', false) == true)
<div id="background-container"></div>
@endif

@stop
