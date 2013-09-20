@section('subContent')

  <div class="container">
    <div class="row-fluid">
      <section>

        <p>As a privileged member, you may set a custom background that will be displayed around the site. The background can be in the form of a color or an image hosted somewhere.</p>
        <p>If you need to upload your image, <a href="http://imgur.com" target="_blank">Imgur</a> is a suitable service.</p>
      </section>
    </div>

    <div class="row-fluid">
      <section>
        {{ Form::open(array('class' => 'form-horizontal')) }}

          <div class="control-group{{ $errors->has('enable') ? ' error' : '' }}">
            <label class="control-label">Custom Background</label>
            <div class="controls">
              <label class="radio inline">
                <input type="radio" name="enable" value="true"{{ $user->getSetting('background.enable', 'false') == 'true' ? ' checked' : '' }}>
                Enable
              </label>

              <label class="radio inline">
                <input type="radio" name="enable" value="false"{{ $user->getSetting('background.enable', 'false') == 'false' ? ' checked' : '' }}>
                Disable
              </label>

              @if($errors->has('enable'))
              <span class="help-block">{{ $errors->first('background.enable') }}</span>
              @endif
            </div>
          </div>

          <div class="control-group{{ $errors->has('color') ? ' error' : '' }}">
            <label class="control-label" for="color">Color</label>
            <div class="controls">
              <input type="text" id="color" name="color" class="input-small" placeholder="e.g. #fff" value="{{ Input::old('color', $user->getSetting('background.color')) }}">
            </div>
          </div>

          <div class="control-group{{ $errors->has('image') ? ' error' : '' }}">
            <label class="control-label" for="image">Image URL</label>
            <div class="controls">
              <input type="text" id="image" name="image" class="input-xxlarge" placeholder="e.g. http://www.example.com/bg.png" value="{{ Input::old('image', $user->getSetting('background.image')) }}">
            </div>
          </div>

          <div class="alert alert-info">
            The following items will be ignored if <strong>Image URL</strong> is left blank.
          </div>

          <div class="control-group{{ $errors->has('attachment') ? ' error' : '' }}">
            <label class="control-label" for="attachment">Image Attachment</label>
            <div class="controls">
              <select id="attachment" name="attachment" class="input-medium">
                <option value="fixed"{{ Input::old('attachment', $user->getSetting('background.attachment')) == 'fixed' ? ' selected' : '' }}>Fixed position.</option>
                <option value="scroll"{{ Input::old('attachment', $user->getSetting('background.attachment')) == 'scroll' ? ' selected' : '' }}>Scroll with page.</option>
              </select>
            </div>
          </div>

          <div class="control-group{{ $errors->has('position_x') || $errors->has('position_y') ? ' error' : '' }}">
            <label class="control-label">Image Position</label>
            <div class="controls">
              <select name="position_x" class="inline input-small">
                <optgroup label="X-Axis">
                  <option value="left"{{ Input::old('position_x', $user->getSetting('background.position.x')) == 'left' ? ' selected' : '' }}>Left</option>
                  <option value="center"{{ Input::old('position_x', $user->getSetting('background.position.x', 'center')) == 'center' ? ' selected' : '' }}>Center</option>
                  <option value="right"{{ Input::old('position_x', $user->getSetting('background.position.x')) == 'right' ? ' selected' : '' }}>Right</option>
                </optgroup>
              </select>

              <select name="position_y" class="inline input-small">
                <optgroup label="Y-Axis">
                  <option value="top"{{ Input::old('position_y', $user->getSetting('background.position.y')) == 'top' ? ' selected' : '' }}>Top</option>
                  <option value="center"{{ Input::old('position_y', $user->getSetting('background.position.y')) == 'center' ? ' selected' : '' }}>Center</option>
                  <option value="bottom"{{ Input::old('position_y', $user->getSetting('background.position.y')) == 'bottom' ? ' selected' : '' }}>Bottom</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="control-group{{ $errors->has('repeat') ? ' error' : '' }}">
            <label class="control-label" for="repeat">Image Tiling</label>
            <div class="controls">
              <select id="repeat" name="repeat" class="input-medium">
                <option value="no-repeat"{{ Input::old('repeat', $user->getSetting('background.repeat')) == 'disabled' ? ' selected' : '' }}>Disabled</option>
                <option value="repeat-x"{{ Input::old('repeat', $user->getSetting('background.repeat')) == 'repeat-x' ? ' selected' : '' }}>Horizontal Only</option>
                <option value="repeat-y"{{ Input::old('repeat', $user->getSetting('background.repeat')) == 'repeat-y' ? ' selected' : '' }}>Vertical Only</option>
                <option value="repeat"{{ Input::old('repeat', $user->getSetting('background.repeat')) == 'repeat' ? ' selected' : '' }}>Both</option>
              </select>
            </div>
          </div>

          <div class="control-group{{ $errors->has('size') ? ' error' : '' }}">
            <label class="control-label" for="size">Image Sizing</label>
            <div class="controls">
              <select id="size" name="size" class="input-medium">
                <option value="auto"{{ Input::old('size', $user->getSetting('background.size')) == 'auto' ? ' selected' : '' }}>Image size.</option>
                <option value="cover"{{ Input::old('size', $user->getSetting('background.size')) == 'cover' ? ' selected' : '' }}>Scale to page.</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes.</button>
            <button type="reset" class="btn">Revert changes.</button>
          </div>

        {{ Form::close() }}
      </section>
    </div>
  </div>

@stop