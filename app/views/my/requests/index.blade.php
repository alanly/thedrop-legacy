@section('content')

  <div class="container">
    <ul class="breadcrumb">
      <li class="active">List Requests</li>
    </ul>
  </div>

  <div class="container">
    <section>
      <p>If there is anything that you would like that isn't currently offered on <span class="brand">the drop</span> you may try to request it here and if possible, it will be uploaded.
        If you would like to have a TV show added to the weekly queue, then make sure to point that out in the notes part of your request.</p>
    </section>

    <section>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th><th>Created On</th><th>Status</th><th>Manage</th>
          </tr>
        </thead>

        <tfoot>
          <tr>
            <th colspan="4"><p class="text-center">&mdash; <em>Would you like to {{ link_to_action('FileRequestController@create', 'create a new request') }}?</em> &mdash;</p></th>
          </tr>
        </tfoot>

        <tbody>
          @if($requestsCount == 0)
            <tr>
              <th colspan="4"><p class="text-center muted">You currently do not have any outstanding requests.</p></th>
            </tr>
          @else
            @foreach($requests as $request)
              <tr{{ $request->status == 0 ? '' : ' class="' . ($request->status > 0 ? 'success' : 'error') . '"' }}>
                <td>
                  {{ link_to_action('FileRequestController@show', $request->title, array('requests' => $request->id)) }}
                </td>
                <td>{{{ (new DateTime($request->created_at))->format('d-M-Y G:i') }}}</td>
                <td>{{ $request->status == 0 ? 'Open' : ($request->status > 0 ? 'Fulfilled' : 'Closed') }}</td>
                <td>
                  @if($request->status == 0)
                  <a href="{{ URL::action('FileRequestController@edit', array('requests' => $request->id)) }}" title="Edit this request."><i class="icon-edit"></i></a>
                  @endif
                </td>
            @endforeach
          @endif
        </tbody>
      </table>
    </section>
  </div>

@stop