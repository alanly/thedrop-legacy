@extends('layouts.application')

@section('pageName')
Privileged Area
@stop

@section('content')
  <div class="container">
    <section>
      <div class="page-header">
        <h3><em>&#8220;For privileged eyes only...&#8221;</em></h3>
      </div>
      <p>You're trying to reach the section at <code>{{{ Request::path() }}}</code> which is available to <strong>privileged</strong> users only.</p>
      <p>In order to achieve privileged status on your account, you just simply have to make a contribution to this site. For more information, please visit the {{ link_to_route('donate', 'donations') }} page.</p>
    </section>
  </div>
@stop