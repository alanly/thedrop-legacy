@section('subContent')

  <div class="container">
    <ul class="breadcrumb">
      <li class="active">List Requests</li>
    </ul>
  </div>

  <div class="container">
    <section>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th><th>Title</th><th>Created On</th><th>Status</th>
          </tr>
        </thead>

        <tbody>
          @if($requestsCount == 0)
            <tr>
              <th colspan="4"><p class="text-center muted">There are currently no requests yet.</p></th>
            </tr>
          @else
            @foreach($requests as $request)
              <tr{{ $request->status == 0 ? '' : ' class="' . ($request->status > 0 ? 'success' : 'error') . '"' }}>
                <td>{{ link_to_action('AdminFileRequestController@show', $request->id, array('request' => $request->id)) }}</td>
                <td>{{ link_to_action('AdminFileRequestController@show', $request->title, array('request' => $request->id)) }}</td>
                <td>{{{ $request->created_at }}}</td>
                <td>{{ $request->status == 0 ? 'Open' : ($request->status > 0 ? 'Fulfilled' : 'Closed') }}</td>
            @endforeach
          @endif
        </tbody>
      </table>
    </section>
  </div>

@stop