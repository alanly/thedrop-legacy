@section('subContent')

  <div class="container">
    <ul class="breadcrumb">
      <li>{{ link_to_action('AdminFileRequestController@index', 'List Requests') }} <span class="divider">/</span></li>
      <li>{{ link_to_action('AdminFileRequestController@show', 'Show Request', array('request' => $request->id)) }} <span class="divider">/</span></li>
      <li>Edit Request</li>
    </ul>
  </div>

  <div class="container">
    <section>
      <div class="row-fluid">
        {{ Form::model($request, array('action' => array('AdminFileRequestController@update', $request->id), 'class' => 'form-horizontal', 'method' => 'put')) }}

          <div class="control-group">
            {{ Form::label('title', 'Title', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('title', null, array('class' => 'input-xxlarge', 'readonly ' => 'readonly')) }}
            </div>
          </div>

          <div class="control-group{{ $errors->has('status') ? ' error' : '' }}">
            {{ Form::label('status', 'Fulfillment Status', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::select('status', array('0' => 'Open', '-1' => 'Closed', '1' => 'Fulfilled')) }}
              @if($errors->has('status'))
              <span class="help-block">{{ $errors->first('status') }}</span>
              @endif
            </div>
          </div>

          <div class="control-group{{ $errors->has('file_id') ? ' error' : '' }}">
            {{ Form::label('file_id', 'Fulfilled By', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::select('file_id', $filesArray, null, array('class' => 'input-xxlarge')) }}
              @if($errors->has('file_id'))
              <span class="help-block">{{ $errors->first('file_id') }}</span
              @endif
            </div>
          </div>

          <div class="control-group">
            {{ Form::label('user_notes', 'User Notes', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::textarea('user_notes', null, array('class' => 'input-xxlarge', 'rows' => '4', 'readonly' => 'readonly')) }}
            </div>
          </div>

          <div class="control-group{{ $errors->has('admin_notes') ? ' error' : '' }}">
            {{ Form::label('admin_notes', 'Admin Notes', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::textarea('admin_notes', null, array('class' => 'input-xxlarge', 'rows' => '4')) }}
              @if($errors->has('admin_notes'))
              <span class="help-block">{{ $errors->first('admin_notes') }}</span>
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