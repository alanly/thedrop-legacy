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
          <input type="email" class="span3" name="email" placeholder="email address" value="{{ Input::old('email', Input::get('email')) }}" required>
          @if ($errors->has('email'))
          <span class="help-block">{{ $errors->first('email') }}</span>
          @endif
        </div>

        <div class="control-group{{ $errors->has('code') ? ' error' : '' }}">
          <input type="text" class="span3" name="code" placeholder="reset code" value="{{ Input::old('code', Input::get('code')) }}" required>
          @if ($errors->has('code'))
          <span class="help-block">{{ $errors->first('code') }}</span>
          @endif
        </div>

        <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
          <input type="password" class="span3" name="password" placeholder="new password" required title="Must be at least 8 characters long.">
          @if ($errors->has('password'))
          <span class="help-block">{{ $errors->first('password') }}</span>
          @endif
        </div>

        <div class="control-group{{ $errors->has('password_confirmation') ? ' error' : '' }}">
          <input type="password" class="span3" name="password_confirmation" placeholder="confirm new password" required title="Must match the value entered above.">
          @if ($errors->has('password_confirmation'))
          <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
          @endif
        </div>

        <div class="control-group">
          <button type="submit" class="btn btn-primary">Reset password.</button>
        </div>
      {{ Form::close() }}
    </div>
  </div>
@stop

@section('script')
<script>
$('input').tooltip({
    container: 'body',
    placement: 'right'
});
</script>
@stop