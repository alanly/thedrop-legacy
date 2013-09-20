@extends('admin.file.master')

@section('content')

    <div class="container">
        <ul class="breadcrumb">
            <li class="active">List Files</li>
        </ul>
    </div>

    <div class="container">
        <div class="row-fluid">
            <section>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Total Available Files</th><th>Total Space Occupied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{{ $totalFiles }}}</td>
                            <td>{{{ RepositoryFile::generatePrettySize($totalSpaceOccupied) }}}</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>

        <div class="row-fluid">
            <section>
                {{ Form::open() }}
                    {{ Form::hidden('action', 'deleteMultiple') }}
                    <table class="table" id="files-table">
                        <thead>
                            <tr>
                                <th></th><th>ID</th><th>Name</th><th>Size</th><th>File Modified At</th><th>Downloads</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6">
                                    <button class="pull-right btn btn-danger btn-small" type="submit"><i class="icon-trash"></i> Delete selected?</button>
                                </th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if ($totalFiles == 0)
                            <tr>
                                <th colspan="6"><p class="muted text-center">There are currently no files available.</p></th>
                            </tr>
                            @endif
                            @foreach ($files as $file)
                            <tr>
                                <td>{{ Form::checkbox('selectedFiles[]', $file->id, false, array('class' => 'checkbox')) }}</td>
                                <td>{{ link_to_action('AdminFileController@show', $file->id, array('file' => $file->id)) }}</td>
                                <td>{{ link_to_action('AdminFileController@show', $file->name, array('file' => $file->id)) }}</td>
                                <td data-sort="{{{ $file->size }}}">{{{ $file->getPrettySize() }}}</td>
                                <td>{{{ date('j-M-o H:i:s', strtotime($file->modified_at)) }}}</td>
                                <td>{{{ count($file->downloads()->groupBy('user_id')->get()) }}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                {{ Form::close() }}
            </section>
        </div>
    </div>

@stop

@section('script')

<script src="//cdnjs.cloudflare.com/ajax/libs/tablesort/1.6.1/tablesort.min.js"></script>
<script>
    new Tablesort(document.getElementById('files-table'));

    $(document).ready(function(){
        $("#files-table .checkbox").change(function(e) {
            if ($(this).is(":checked")) {
                $(this).closest('tr').addClass('warning');
            } else {
                $(this).closest('tr').removeClass('warning');
            }
        });
    });
</script>
@stop