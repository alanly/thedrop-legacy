@extends('layouts.application')

@section('pageName')
Manage Invites
@stop

@section('content')
<div class="container nav-container">
    <div class="row-fluid">
        <nav class="nav nav-pills">
            <li{{ Route::currentRouteAction() == 'AdminInviteController@index' ? ' class="active"' : '' }}>{{ link_to_action('AdminInviteController@index', 'List Invites') }}</li>
            <li{{ Route::currentRouteAction() == 'AdminInviteController@create' ? ' class="active"' : '' }}>{{ link_to_action('AdminInviteController@create', 'Create Invites') }}</li>
        </nav>
    </div>
</div>

<div class="container">
  @yield('subContent')
</div>
@stop
