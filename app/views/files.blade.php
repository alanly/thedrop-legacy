@extends('layouts.application')

@section('style')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen" />
@stop

@section('pageName')
Files
@stop

@section('content')

    <div class="container">
        <div class="row-fluid">
            <section>
                <table id="files-table" class="table files-table">
                    <thead>
                        <tr>
                            <th class="no-sort hidden-phone"></th>
                            <th>Description</th>
                            <th>Size</th>
                            <th class="hidden-phone">Modified</th>
                            @if ($user->isAdmin())
                            <th class="no-sort"><span class="hidden-phone">Manage</span></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($files) == 0)
                        <tr>
                            <td colspan="4"><p class="text-center muted">There are currently no files available.</p></td>
                        </tr>
                        @endif
                        @foreach ($files as $file)
                        <tr{{ $user->hasDownloaded($file) ? ' class="downloaded"' : '' }}>
                            <td class="hidden-phone"><i class="icon-{{{ $file->getIconType() }}}"></i></td>
                            <td>
                                @include('layouts.file_description')
                                @if ($user->getSetting('dropbox.enable', 'false') == 'true' && $file->size < 3221225472)
                                    <div class="save-dropbox-container">
                                        <a href="{{ URL::to('/download', array($user->api_key, rawurlencode($file->name)), false) }}" class="save-dropbox" data-filename="{{{ $file->getPrettyFilename() }}}" title="Save this file to your Dropbox account directly."><i class="icon-dropbox"></i></a>
                                    </div>
                                @endif
                            </td>
                            <td data-sort="{{{ $file->size }}}">{{{ $file->getPrettySize() }}}</td>
                            <td data-sort="{{{ $file->modified_at }}}" class="hidden-phone">{{{ date('j-M-o H:i', strtotime($file->modified_at)) }}}</td>
                            @if ($user->isAdmin())
                            <td>
                                {{ Form::open(array('name' => 'destroyFile_' . $file->id,'class' => 'inline', 'method' => 'delete', 'url' => URL::action('AdminFileController@destroy', array('file' => $file->id)))) }}
                                    <a href="#" onClick="document.forms['destroyFile_{{ $file->id }}'].submit()" title="Delete file immediately."><i class="icon-trash"></i></a>
                                {{ Form::close() }}

                                <a href="{{ URL::action('AdminFileController@show', array('file' => $file->id)) }}" title="Display file details."><i class="icon-eye-open"></i></a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>

@stop

@section('script')
@if ($user->getSetting('dropbox.enable', 'false') == 'true')
<script src="https://www.dropbox.com/static/api/1/dropins.js" id="dropboxjs" data-app-key="7ppzsnx7kgsyquo"></script>
<script>
    $('.save-dropbox').click(function() {
        var url = $(this).attr('href');
        var filename = $(this).data('filename');
        var timestamp = (new Date()).getTime();

        var alertBlock = '<div id="' + timestamp + '-container" class="container"><div class="alert alert-block alert-info">';
            alertBlock += '<p><i class="icon-dropbox"></i> Saving <code>' + filename + '</code> to your Dropbox. You <strong>do not</strong> have to remain on this page for the process to complete.</p>';
            alertBlock += '<div class="progress progress-striped active" style="margin-top: 15px;margin-bottom: 10px;"><div id="' + timestamp + '-bar" class="bar" style="width: 100%;"></div></div></div></div>';

        $('#message-container').append(alertBlock);
        var messageBlock = $('#' + timestamp + '-container');

        var options = {
            files: [{'url': url, 'filename': filename}],
            success: function() {
                var alertBlock = '<div class="container"><div class="alert alert-block alert-success">';
                alertBlock += '<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
                alertBlock += '<i class="icon-save"></i> Successfully saved <code>' + filename + '</code> to your Dropbox!</div></div>';
                
                messageBlock.replaceWith(alertBlock);
            },
            progress: function(progress) {
                $('#' + timestamp + '-bar').css('width', (progress * 100) + '%');
            },
            cancel: function() {
                messageBlock.remove();
            },
            error: function(errmsg) {
                var alertBlock = '<div class="container"><div class="alert alert-block alert-error">';
                alertBlock += '<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>';
                alertBlock += '<p><i class="icon-warning-sign"></i> An error occurred while trying to save <code>' + filename + '</code> to your Dropbox. The file <em>may</em> have transferred successfully; you may want to check your Dropbox account before trying again.</p>';
                alertBlock += '<br><pre>' + errmsg + '</pre></div></div>';
                
                messageBlock.replaceWith(alertBlock);
            }
        };

        Dropbox.save(options);

        return false;
    });
</script>
@endif
<script src="//cdnjs.cloudflare.com/ajax/libs/tablesort/1.6.1/tablesort.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script>
    new Tablesort(document.getElementById('files-table'));
    $('a').tooltip({
        container: 'table',
        placement: 'bottom'
    });

    $(document).ready(function() {
        $('.fancybox').fancybox();
    });
</script>
@stop