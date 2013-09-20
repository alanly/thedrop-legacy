@section('subContent')

  <div class="container">
    <section class="row-fluid">
      {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
        <label>
          Start:
          <input type="date" name="periodStart" class="input-medium" value="{{ $periodStart }}">
        </label>
        &nbsp;
        <label>
          End:
          <input type="date" name="periodEnd" class="input-medium" value="{{ $periodEnd }}">
        </label>

        <button type="submit" class="btn">Apply</button>
      {{ Form::close() }}
    </section>

    <section class="row-fluid">
      <div id="downloadGraph"></div>
    </section>
  </div>

@stop

@section('script')
  <script src="//www.google.com/jsapi"></script>
  <script>
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Date');
      data.addColumn('number', 'Downloads');
      data.addRows([
          @foreach($downloadsPerDay as $date => $count)
          [ new Date("{{ $date }}"), {{ $count }} ],
          @endforeach
        ]);

      var options = {
        'title': 'Downloads Over Period',
        'legend': {
          'position': 'none'
        },
        'height': 400,
        'width': 1100,
        'hAxis': {
          'viewWindowMode': 'maximized'
        },
      };

      var chart = new google.visualization.LineChart(document.getElementById('downloadGraph'));

      chart.draw(data, options);

    }
  </script>
@stop