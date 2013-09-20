@section('subContent')
  <div class="container">
    <div class="row-fluid">
      <section>
        <p>Enabling <em>Dropbox</em> integration allows you to save the shows and moves you want directly onto your Dropbox account in just two-clicks.</p>
        <p>The benefits of saving to your Dropbox account is that files are automatically background synchronized in batch to all your computers, and furthermore you can stream the content directly from Dropbox's web app within a matter of seconds rather than sitting though a 20 minute download.</p>
        <p><strong>P.S.</strong> You will need to have Javascript enabled in order for Dropbox integration to work.</p>
        <hr>
        <p><h6>Dropbox saving is currently disabled for files that are 3 GB and larger due to issues with Dropbox's API when transferring files of great sizes.</h6></p>
      </section>
    </div>
    <div class="row-fluid">
      <section>
        {{ Form::open(array('class' => 'form-horizontal')) }}
          <div class="control-group{{ $errors->has('enable') ? ' error' : '' }}">
            <label class="control-label">Dropbox Integration?</label>
            <div class="controls">
              <label class="radio inline">
                <input type="radio" name="enable" value="true"{{ $user->getSetting('dropbox.enable', 'false') == 'true' ? ' checked' : '' }}>
                Enable
              </label>
              <label class="radio inline">
                <input type="radio" name="enable" value="false"{{ $user->getSetting('dropbox.enable', 'false') == 'true' ? '' : ' checked' }}>
                Disable
              </label>

              @if($errors->has('enable'))
              <span class="help-block">{{ $errors->first('enable') }}</span>
              @endif
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes.</button>
          </div>
        {{ Form::close() }}
      </section>
    </div>
  </div>
@stop