@extends('admin.file.master')

@section('style')
  <style>
    #manualMetadataModal {
      width: 800px;
      margin-left: -400px;
    }
  </style>
@stop

@section('content')

  <div class="container">
      <ul class="breadcrumb">
          <li>{{ link_to_action('AdminFileController@index', 'List Files') }} <span class="divider">/</span></li>
          <li class="active">Show File</li>
      </ul>
  </div>

  <div class="container">
    <div class="row-fluid">
      <section>
        <div class="row-fluid">
          <div class="page-header">
            <h3>{{{ $file->name }}}</h3>
          </div>
        </div>

        <div class="row-fluid">
          <div class="span4">
            <dl class="dl-horizontal">
              <dt>File Size</dt>
              <dd>{{{ $file->getPrettySize() }}}</dd>

              <dt>File Type</dt>
              <dd><code>{{{ $file->type }}}</code></dd>

              <dt>Modified At</dt>
              <dd>{{{ $file->modified_at }}}</dd>

              <dt>Total Downloads</dt>
              <dd>{{{ count($downloads) }}}</dd>
            </dl>
          </div>

          <div class="span8">
            {{ Form::open(array('method' => 'put')) }}
              <input type="hidden" name="action" value="autoMetadata">

              <div class="btn-group">
                <button type="submit" class="btn btn-info"><i class="icon-barcode"></i> Automatically retrieve metadata</button>
                <a class="btn btn-info" href="#manualMetadataModal" role="button" data-toggle="modal">Manually retrieve metadata</a>
              </div>
            {{ Form::close() }}

            {{ Form::open(array('method' => 'delete')) }}
              <button type="submit" class="btn btn-danger"><i class="icon-trash"></i> Delete this file</button>
            {{ Form::close() }}
          </div>
        </div>
      </section>
    </div>

    <div class="row-fluid">
      <section>
        <div class="page-header">
          <h4>Metadata</h4>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>Key</th><th>Value</th>
            </tr>
          </thead>
          <tbody>
            @if ($file->metadata()->count() == 0)
            <tr>
              <td colspan="2"><p class="muted text-center">There is no metadata for this file available.</p></td>
            </tr>
            @endif
            @foreach ($file->metadata()->get() as $meta)
            <tr>
              <td>{{{ $meta->key }}}</td><td>{{{ $meta->value }}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </section>
    </div>

    <div class="row-fluid">
      <section>
        <div class="page-header">
          <h4>Downloads</h4>
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>ID</th><th>Name</th><th>Downloaded At</th><th>IP Address</th>
            </tr>
          </thead>
          <tbody>
            @if ($file->downloads()->count() == 0)
            <tr>
              <td colspan="4"><p class="text-center muted">There have been no downloads yet.</p></td>
            </tr>
            @endif
            @foreach ($downloads as $download)
            <tr>
              <td>{{ link_to_action('AdminUserController@show', $download->user()->first()->id, array('user' => $download->user()->first()->id)) }}</td>
              <td>{{{ $download->user()->first()->name }}}</td>
              <td>{{{ $download->updated_at }}}</td>
              <td><a href="http://whois.arin.net/ui/query.do?q={{{ $download->ip_address }}}" target="_blank">{{{ $download->ip_address }}}</a></td>
            </tr>
            @endforeach
        </table>
      </section>
    </div>
  </div>

  <div id="manualMetadataModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
      <h3>Retrieve Metadata from Trakt.tv</h3>
    </div>

    <div class="modal-body">
      {{ Form::open(array(
          'name' => 'manualMetadataForm',
          'class' => 'form-horizontal',
          'method' => 'put',
          'url' => URL::action('AdminFileController@update', array('file' => $file->id))
        )) }}

        <input type="hidden" name="action" value="manualMetadata">

        <div class="control-group">
          <label class="control-label" for="name">File Name</label>
          <div class="controls">
            <input type="text" class="input-xxlarge" name="name" id="name" value="{{{ $file->name }}}" readonly>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="type">File Type</label>
          <div class="controls">
            <select name="type" id="type">
              <option>Select a type...</option>
              <option value="video.tv"{{ $file->type == 'video.tv' ? ' selected' : '' }}>video.tv</option>
              <option value="video.movie"{{ $file->type == 'video.movie' ? ' selected' : '' }}>video.movie</option>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="quality">Media Quality</label>
          <div class="controls">
            <input type="text" name="quality" id="quality" value="{{ $file->getMetadata('media.quality') }}" required>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="slug">Slug</label>
          <div class="controls">
            <input type="text" class="input-xlarge" name="slug" id="slug" placeholder="the-social-network-2010" required>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="season">Season #</label>
          <div class="controls">
            <input type="number" class="input-small" name="season" id="season" min="0" value="{{{ $file->getMetadata('show.season') }}}">
            <span class="help-inline"><small class="muted">TV-only</small></span>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="episode">Episode #</label>
          <div class="controls">
            <input type="number" class="input-small" name="episode" id="episode" min="0" value="{{{ $file->getMetadata('season.episode') }}}">
            <span class="help-inline"><small class="muted">TV-only</small></span>
          </div>
        </div>
      {{ Form::close() }}
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-primary" role="button" onClick="document.forms['manualMetadataForm'].submit()">Retrieve Metadata</button>
    </div>
  </div>

@stop