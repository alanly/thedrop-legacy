@extends('layouts.master')

@section('style')
  <link href="/css/auth.min.css" rel="stylesheet">
@stop

@section('body')
    <div class="center-unit box">
      <div class="row">
        <div class="span3">
          <a href="{{ URL::route('login') }}"><h3 class="brand">the drop.</h3></a>
        </div>
      </div>

       @yield('center-box')
    </div>
@stop

