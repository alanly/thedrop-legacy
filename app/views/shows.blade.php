@extends('layouts.application')

@section('pageName')
List of Shows
@stop

@section('content')

  <div class="container">
    <div class="row-fluid">
      <section>
        <p>These are all the shows that are actively being followed right now.</p>
        <p>If you would like to follow new content, please make a {{ link_to_action('FileRequestController@index', 'request') }}!</p>
      </section>
    </div>

    <div class="row-fluid">
      <section>
        <table id="show-table" class="table">
          <thead>
            <tr>
              <th>Name</th><th>Airtime (<abbr title="Eastern Time">EST</abbr>)</th><th>Network</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="3">{{{ count($shows) }}} shows being followed.</th>
            </tr>
          </tfoot>
          <tbody>
            @if( count($shows) == 0 )
              <tr>
                <th colspan="3"><p class="muted text-center">There are current no shows being followed.</p></th>
              </tr>
            @else
              @foreach( $shows as $show )
                <tr>
                  <td><a href="http://imdb.com/title/{{{ $show->imdb_id }}}" target="_blank" title="View show details on IMDb.">{{{ $show->title }}}</a></td>
                  <td>{{{ $show->air_day }}} &#64; {{{ $show->air_time }}}</td>
                  <td>{{{ $show->network }}}</td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </section>
    </div>
  </div>

@stop

@section('script')

  <script src="//cdnjs.cloudflare.com/ajax/libs/tablesort/1.6.1/tablesort.min.js"></script>
  <script>
    new Tablesort(document.getElementById('show-table'));
  </script>

@stop