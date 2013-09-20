@section('subContent')

  <div class="container">
    <section>
      <div class="row-fluid">
        {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
          <select name="orderIn" class="input-medium">
            <option value="desc"{{ Input::old('orderIn') == 'desc' ? ' selected' : '' }}>Descending</option>
            <option value="asc"{{ Input::old('orderIn') == 'asc' ? ' selected' : '' }}>Ascending</option>
          </select>

          <select name="results" class="input-small">
            <option value="10"{{ Input::old('results') == 10 ? ' selected' : '' }}>10</option>
            <option value="20"{{ Input::old('results') == 20 ? ' selected' : '' }}>20</option>
            <option value="50"{{ Input::old('results') == 50 ? ' selected' : '' }}>50</option>
            <option value="100"{{ Input::old('results') == 100 ? ' selected' : '' }}>100</option>
          </select>

          <button type="submit" class="btn"><i class="icon-filter"></i> Filter</button>
        {{ Form::close() }}
      </div>
    </section>
  </div>

  <div class="container">
    <section>
      <div class="row-fluid">
        <table class="table">
          <thead>
            <tr>
              <th>File Name</th><th>Date &amp; Time</th><th>Client Address</th>
            </tr>
          </thead>
          <tbody>
            @if($totalDownloads == 0)
              <tr>
                <td colspan="3"><p class="muted text-center">You have no downloaded anything yet.</td>
              </tr>
            @else
              @foreach($downloads as $download)
                <tr>
                  <td>{{{ $download->file()->first()->name }}}</td>
                  <td>{{{ date('j-M-o H:i', strtotime($download->created_at)) }}}</td>
                  <td><a href="http://whois.arin.net/ui/query.do?q={{ $download->ip_address }}" target="_blank">{{{ $download->ip_address }}}</a></td>
                </tr>
              @endforeach
            @endif
        </table>
      </div>

      <div class="row-fluid">
        {{ $downloads->appends(array('orderIn' => Input::old('orderIn'), 'results' => Input::old('results')))->links() }}
      </div>
    </section>
  </div>

@stop