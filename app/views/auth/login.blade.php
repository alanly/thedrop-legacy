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
            {{ Form::open() }}
                <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
                    <input type="email" class="span3" name="email" placeholder="email address" value="{{ Input::old('email') }}">
                    @if( $errors->has('email') )
                    <small class="help-block">{{ $errors->first('email') }}</small>
                    @endif
                </div>
                <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
                    <input type="password" class="span3" name="password" placeholder="password">
                    @if( $errors->has('password') )
                    <small class="help-block">{{ $errors->first('password') }}</small>
                    @endif
                </div>
                <div class="control-group">
                    <label class="checkbox">
                        <small>Remember me?</small>
                        <input type="checkbox" id="remember" name="remember">
                    </label>
                </div>
                <div class="control-group">
                    <button type="submit" class="btn btn-primary">Sign in.</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="link">
        <a class="register-link" href="{{ URL::route('register') }}">Register for an account.</a><br>
        <a href="{{ URL::route('reset') }}">Forgot your password?</a>
    </div>
@stop
