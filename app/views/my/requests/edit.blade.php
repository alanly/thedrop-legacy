@section('content')

  <div class="container">
    <ul class="breadcrumb">
      <li>{{ link_to_action('FileRequestController@index', 'List Requests') }} <span class="divider">/</span></li>
      <li>{{ link_to_action('FileRequestController@show', 'Show Request', array('requests' => $request->id)) }} <span class="divider">/</span></li>
      <li>Edit Request</li>
    </ul>
  </div>

  <div class="container">
    <section>
      <div class="row-fluid">
        {{ Form::model($request, array('action' => array('FileRequestController@update', $request->id), 'class' => 'form-horizontal', 'method' => 'put')) }}

          <div class="control-group{{ $errors->has('title') ? ' error' : '' }}">
            {{ Form::label('title', 'Title', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('title', null, array('class' => 'input-xxlarge', 'placeholder' => 'e.g. House of Cards (2013) S01E05', 'title' => 'A short and concise description of what you want.', 'required' => 'required')) }}
              @if($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
              @endif
            </div>
          </div>

          <div class="control-group{{ $errors->has('notes') ? ' error' : '' }}">
            {{ Form::label('user_notes', 'User Notes', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::textarea('user_notes', null, array('class' => 'input-xxlarge', 'rows' => '4', 'placeholder' => 'e.g. Please add the show to the weekly queue.')) }}
              @if($errors->has('notes'))
                <span class="help-block">{{ $errors->first('notes') }}</span>
              @endif
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes.</button>
            <button type="reset" class="btn">Clear changes.</button>
          </div>
        {{ Form::close() }}
      </div>
    </section>
  </div>

@stop