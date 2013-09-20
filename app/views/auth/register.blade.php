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
                <div class="control-group{{ $errors->has('inviteCode') ? ' error' : '' }}">
                    <input type="text" class="span3" name="inviteCode" placeholder="invitation code" value="{{ Input::old('inviteCode') }}">
                    @if ($errors->has('inviteCode'))
                    <span class="help-block">{{ $errors->first('inviteCode') }}</span>
                    @endif
                </div>
                <br>
                <div class="control-group{{ $errors->has('name') ? ' error' : '' }}">
                    <input type="text" class="span3" name="name" placeholder="name" required value="{{ Input::old('name') }}">
                    @if ($errors->has('name'))
                    <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
                    <input type="email" class="span3" name="email" placeholder="email address" required title="You will receive a confirmation email at this address. Make sure it's accessible." value="{{ Input::old('email') }}">
                    @if ($errors->has('email'))
                    <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <br>
                <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
                    <input type="password" class="span3" name="password" placeholder="password" required title="Must be at least 8 characters long.">
                    @if ($errors->has('password'))
                    <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="control-group{{ $errors->has('password_confirmation') ? ' error' : '' }}">
                    <input type="password" class="span3" name="password_confirmation" placeholder="password confirmation" required title="Must match the value entered above.">
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
                <br>
                <div class="control-group">
                    <button type="submit" class="btn btn-primary">Create an account.</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>

    <a class="link" href="{{ URL::route('login') }}">Sign into an existing account.</a>
@stop

@section('script')
<script>
$('input').tooltip({
    container: 'body',
    placement: 'right'
});
</script>
@stop
