@extends('layouts.application')

@section('pageName')
My Profile
@stop

@section('content')

    <div class="container">
        <div class="row-fluid">
            <div class="span3">
                <section>
                    <img class="img-circle" src="{{ $user->getGravatarUrl(250) }}" title="Gravatar Profile Image">
                    <br><br>
                    <p class="text-center">Change your profile image at <a href="http://gravatar.com/">Gravatar</a>.</p>
                </section>
            </div>
            
            <div class="span9">
                <section>
                    {{ Form::model($user, array('action' => 'ProfileController@postIndex', 'class' => 'form-horizontal')) }}
                        <div class="control-group{{ $errors->has('name') ? ' error' : '' }}">
                            {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::text('name', null, array('required' => 'required')) }}
                                @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="control-group{{ $errors->has('email') ? ' error' : '' }}">
                            {{ Form::label('email', 'Email Address', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::input('email', 'email', null, array('required' => 'required')) }}
                                @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
                            {{ Form::label('password', 'Current Password', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::password('password', array('required' => 'required')) }}
                                @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="alert alert-block alert-warning">
                            Only fill-out the following if you intend to change your password. If not, you may leave them blank.
                        </div>
                        <div class="control-group{{ $errors->has('new_password') ? ' error' : '' }}">
                            {{ Form::label('new_password', 'New Password', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::password('new_password', array('title' => 'Must be at least 8 characters long.')) }}
                                @if ($errors->has('new_password'))
                                <span class="help-block">{{ $errors->first('new_password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="control-group{{ $errors->has('new_password_confirmation') ? ' error' : '' }}">
                            {{ Form::label('new_password_confirmation', 'Confirm New Password', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::password('new_password_confirmation', array('title' => 'Must match the value entered above.')) }}
                                @if ($errors->has('new_password_confirmation'))
                                <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="control-group">
                            {{ Form::label('api_key', 'API Key', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::text('api_key', null, array('disabled' => 'disabled')) }}
                            </div>
                        </div>
                        <div class="control-group">
                            {{ Form::label('reset_api_key', 'Reset API Key?', array('class' => 'control-label')) }}
                            <div class="controls">
                                {{ Form::checkbox('reset_api_key') }}
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save changes.</button>
                            <button type="reset" class="btn">Clear changes.</button>
                        </div>
                    {{ Form::close() }}
                </section>
            </div>
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