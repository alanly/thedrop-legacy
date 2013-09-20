@extends('layouts.application')

@section('pageName')
My Downloads
@stop

@section('content')

  <div class="container nav-container">
    <div class="nav nav-pills">
      <li{{ Route::currentRouteUses('DownloadsController@getStatistics') ? ' class="active"' : '' }}>{{ link_to_action('DownloadsController@getStatistics', 'Download Statistics') }}</li>
      <li{{ Route::currentRouteUses('DownloadsController@getListing') ? ' class="active"' : '' }}>{{ link_to_action('DownloadsController@getListing', 'List Downloads') }}</li>
    </div>
  </div>

  @yield('subContent')

@stop