@extends('auth.master')

@section('center-box')
    <div class="row">
        <div class="span3">
            @if ( Session::has('actionFlash.message') )
            <div class="alert alert-block alert-{{ Session::get('actionFlash.status', 'error') }}">
                {{ Session::get('actionFlash.message') }}
            </div>
            <br>
            @endif
            {{ Form::open(array('method' => 'get')) }}
                <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
                    <input type="email" class="span3" name="email" placeholder="email address" value="{{ Input::old('email', Input::get('email')) }}" required>
                    @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="control-group{{ $errors->has('code') ? ' error' : '' }}">
                    <input type="text" class="span3" name="code" placeholder="activation code" value="{{ Input::old('code', Input::get('code')) }}" required>
                    @if ($errors->has('code'))
                    <span class="help-block">{{ $errors->first('code') }}</span>
                    @endif
                </div>
                <div class="control-group">
                    <button type="submit" class="btn btn-primary">Activate account.</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
