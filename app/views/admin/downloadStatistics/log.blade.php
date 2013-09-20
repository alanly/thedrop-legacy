@extends('admin.downloadStatistics.master')

@section('subContent')
  <div class="container">
    <div class="row-fluid">
      <section>
        {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
          <select name="order" class="input-medium">
            <option value="desc"{{ Input::old('order') == 'desc' ? ' selected' : '' }}>Descending</option>
            <option value="asc"{{ Input::old('order') == 'asc' ? ' selected' : '' }}>Ascending</option>
          </select>

          <select name="count" class="input-small">
            <option value="10"{{ Input::old('count') == 10 ? ' selected' : '' }}>10</option>
            <option value="20"{{ Input::old('count') == 20 ? ' selected' : '' }}>20</option>
            <option value="50"{{ Input::old('count') == 50 ? ' selected' : '' }}>50</option>
            <option value="100"{{ Input::old('count') == 100 ? ' selected' : '' }}>100</option>
            <option value="{{{ $totalCount }}}"{{ Input::old('count') == $totalCount ? ' selected' : '' }}>All</option>
          </select>

          <button type="submit" class="btn"><i class="icon-filter"></i> Filter</button>

          <span class="help-inline">There are <strong>{{{ $totalCount }}}</strong> download entries.</span>
        {{ Form::close() }}
      </section>
    </div>

    <div class="row-fluid">
      <section>
        <div class="row-fluid">
          <table class="table">
            <thead>
              <tr>
                <th>File Name</th><th>User</th><th>Date &amp; Time</th><th>Client Address</th>
              </tr>
            </thead>
            <tbody>
              @if( $totalCount == 0 )
                <tr>
                  <th colspan="4"><p class="muted text-center">There are no downloads yet.</p></th>
                </tr>
              @else
                @foreach( $downloads as $download )
                  <tr>
                    <td>{{{ $download->file()->first()->name }}}</td>
                    <td>{{ link_to_action('AdminUserController@show', $download->user()->first()->name, array('user' => $download->user()->first()->id)) }}</td>
                    <td>{{{ date('j-M-o H:i', strtotime($download->created_at)) }}}
                    <td><a href="http://whois.arin.net/ui/query.do?q={{ $download->ip_address }}" target="_blank">{{{ $download->ip_address }}}</a></td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>

        <div class="row-fluid">
          {{ $downloads->appends(array('order' => Input::old('order'), 'count' => Input::old('count')))->links() }}
        </div>
      </section>
    </div>
  </div>
@stop