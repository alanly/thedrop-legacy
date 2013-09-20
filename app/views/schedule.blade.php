@extends('layouts.application')

@section('pageName')
Schedule
@stop

@section('content')

    <div class="container">
        <section>
            <div class="row-fluid">
                <iframe 
                    class="google-calendar-embed" 
                    src="//www.google.com/calendar/embed?mode=WEEK&showTitle=0&amp;showCalendars=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src={{ $src }}&amp;color=%232952A3&amp;ctz=America%2FNew_York">
                </iframe>
            </div>
        </section>
    </div>

@stop