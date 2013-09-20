@extends('admin.downloadStatistics.master')

@section('subContent')

  <div class="container">
    <div class="row-fluid">
      <section>
        {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
          <label>
            Start:
            <input type="date" name="periodStart" class="input-medium" value="{{{ $periodStart->format('Y-m-d') }}}">
          </label>

          &nbsp;

          <label>
            End:
            <input type="date" name="periodEnd" class="input-medium" value="{{{ $periodEnd->format('Y-m-d') }}}">
          </label>

          <button type="submit" class="btn">Apply</button>
        {{ Form::close() }}
      </section>
    </div>
  </div>

  <div class="container">
    <div class="row-fluid">
      <section>
        <table class="table">
          <thead>
            <tr>
              <th colspan="2"><h4>From the period of <em>{{{ $periodStart->format('F j, Y') }}}</em> to <em>{{{ $periodEnd->format('F j, Y') }}}</em>...</h4></th>
            </tr>
          </thead>
          <tbody>
            @foreach($statistics as $key => $value)
              <tr>
                <th>{{ trans("admin/statistics/titles.{$key}") }}</th>
                <td>{{ $value }}</td>
            @endforeach
          </tbody>
        </table>
      </section>
    </div>
  </div>

@stop