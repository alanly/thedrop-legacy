@section('content')

  <div class="container">
    <ul class="breadcrumb">
      <li>{{ link_to_action('FileRequestController@index', 'List Requests') }} <span class="divider">/</span></li>
      @if($request->status == 0)
        <li class="active">Show Request <span class="divider">/</span></li>
        <li>{{ link_to_action('FileRequestController@edit', 'Edit Request', array('requests' => $request->id)) }}</li>
      @else
        <li class="active">Show Request</li>
      @endif
    </ul>
  </div>

  <div class="container">
    <section>
      <div class="row-fluid">
        <div class="page-header">
          <h3>{{{ $request->title }}}</h3>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span8">
          <dl class="dl-horizontal">
            <dt>Created on</dt>
            <dd>{{{ (new DateTime($request->created_at))->format('d-M-Y G:i') }}}</dd>

            <dt>Last updated on</dt>
            <dd>{{{ (new DateTime($request->updated_at))->format('d-M-Y G:i') }}}</dd>

            <dt>Fulfillment status</dt>
            <dd>{{ $request->status == 0 ? 'Open' : ($request->status > 0 ? 'Fulfilled' : 'Closed') }}</dd>

            <dt>Fulfilled on</dt>
            <dd>{{{ $request->status > 0 ? (new DateTime($request->fulfilled_at))->format('d-M-Y G:i') : '&mdash;' }}}</dd>

            <dt>Fulfilled with</dt>
            <dd>{{ $request->status > 0 ? link_to_route('download', $file->name, array('key' => $user->api_key, 'filename' => $file->name)) : '&mdash;' }}</dd>

            <dt>User notes</dt>
            <dd>{{{ $request->user_notes }}}&nbsp;</dd>

            <dt>Admin notes</dt>
            <dd>{{{ $request->admin_notes }}}&nbsp;</dd>
          </dl>
        </div>

        <div class="span4">
          {{ Form::open(array('class' => 'inline', 'action' => array('FileRequestController@destroy', $request->id), 'method' => 'delete')) }}
            <button type="submit" class="btn btn-danger"><i class="icon-trash"></i> Delete request.</button>
          {{ Form::close() }}
        </div>
      </div>
    </section>
  </div>

@stop