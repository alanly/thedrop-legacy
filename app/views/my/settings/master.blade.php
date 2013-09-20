@extends('layouts.application')

@section('pageName')
My Settings
@stop

@section('content')

  <div class="container">
    <ul class="nav nav-pills">
      <li{{ Request::is('my/settings/background') ? ' class="active"' : '' }}>{{ link_to_action('MySettingsController@getBackground', 'Background') }}</li>
      <li{{ Request::is('my/settings/dropbox') ? ' class="active"' : '' }}>{{ link_to_action('MySettingsController@getDropbox', 'Dropbox') }}</li>
    </ul>
  </div>

  @yield('subContent')

@stop