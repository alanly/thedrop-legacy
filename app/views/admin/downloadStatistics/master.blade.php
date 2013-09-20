@extends('layouts.application')

@section('pageName')
Download Statistics
@stop

@section('content')
  <div class="container">
    <div class="row-fluid">
      <ul class="nav nav-pills">
        <li{{ Route::currentRouteUses('AdminDownloadStatisticsController@getIndex') ? ' class="active"' : '' }}>{{ link_to_action('AdminDownloadStatisticsController@getIndex', 'Statistics') }}</li>
        <li{{ Route::currentRouteUses('AdminDownloadStatisticsController@getGraph') ? ' class="active"' : '' }}>{{ link_to_action('AdminDownloadStatisticsController@getGraph', 'Graphs') }}</li>
        <li{{ Route::currentRouteUses('AdminDownloadStatisticsController@getListing') ? ' class="active"' : '' }}>{{ link_to_action('AdminDownloadStatisticsController@getListing', 'Log') }}</li>
      </ul>
    </div>
  </div>

  @yield('subContent')
@stop