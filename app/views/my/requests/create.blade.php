@section('content')

  <div class="container">
    <ul class="breadcrumb">
      <li>{{ link_to_action('FileRequestController@index', 'List Requests') }} <span class="divider">/</span></li>
      <li class="active">Create a Request</li>
    </ul>
  </div>

  <div class="container">
    <section>
      <div class="row-fluid">
        {{ Form::open(array('class' => 'form-horizontal', 'url' => URL::action('FileRequestController@store'))) }}

          <div class="control-group{{ $errors->has('title') ? ' error' : '' }}">
            <label class="control-label" for="title">Title</label>
            <div class="controls">
              <input type="text" id="title" class="input-xxlarge" name="title" placeholder="e.g. House of Cards (2013) S01E05" title="A short and concise description of what you want." value="{{{ Input::old('title') }}}" required>
              @if($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
              @endif
            </div>
          </div>

          <div class="control-group{{ $errors->has('notes') ? ' error' : '' }}">
            <label class="control-label" for="notes">Notes</label>
            <div class="controls">
              <textarea id="notes" class="input-xxlarge" name="notes" rows="4" placeholder="e.g. Please add the show to the weekly queue.">{{{ Input::old('notes') }}}</textarea>
              @if($errors->has('notes'))
                <span class="help-block">{{ $errors->first('notes') }}</span>
              @endif
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create request.</button>
          </div>
        {{ Form::close() }}
      </div>
    </section>
  </div>

@stop