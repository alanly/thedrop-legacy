@if ($file->type == 'audio.album')

  <a href="{{ URL::to('/download', array($user->api_key, $file->name), false) }}">
    {{{ $file->getMetadata('album.artist') }}} &ndash; <em>{{{ $file->getMetadata('album.title') }}}</em>
  </a>
  <small class="muted">[{{{ $file->getMetadata('media.quality') }}}]</small>

@elseif ($file->type == 'video.tv')

  <a 
    href="{{ URL::to('/download', array($user->api_key, $file->name), false) }}"
    title="{{{ $file->getMetadata('episode.overview') }}}">
    {{{ $file->getMetadata('show.title') }}}
    {{ $file->getMetadata('episode.title') ? ' &ndash; <em>' . $file->getMetadata('episode.title') . '</em>' : '' }}
  </a>
  <small class="muted">
    {{{ $file->getMetadata('show.season') ? 'Season ' . $file->getMetadata('show.season') : '' }}}
    {{{ $file->getMetadata('season.episode') ? ' Episode ' . $file->getMetadata('season.episode') : ''}}}
    [{{{ $file->getMetadata('media.quality') }}}]
    @if ($file->getMetadata('video.screenshot'))
    <a href="{{ URL::secure($file->getMetadata('video.screenshot')) }}" target="_blank" title="View screenshot of this episode." class="fancybox"><i class="icon-picture"></i></a>
    @endif
  </small>

@elseif ($file->type == 'video.movie')

  <a 
    href="{{ URL::to('/download', array($user->api_key, $file->name), false) }}"
    title="{{{ $file->getMetadata('movie.overview') }}}">
    {{{ $file->getMetadata('movie.title') }}} ({{{ $file->getMetadata('movie.year') }}})
  </a>
  <small class="muted">
    [{{{ $file->getMetadata('media.quality') }}}]
    @if ($file->getMetadata('video.imdb.id'))
    <a class="imdb" href="http://imdb.com/title/{{{ $file->getMetadata('video.imdb.id') }}}" target="_blank" title="Look up movie on IMDb">IMDb</a>
    @endif
    @if ($file->getMetadata('video.poster'))
    <a href="{{ $file->getMetadata('video.poster') }}" target="_blank" title="View poster for this movie." class="fancybox"><i class="icon-picture"></i></a>
    @endif
  </small>

@else

  <a href="{{ URL::to('/download', array($user->api_key, $file->name), false) }}">
    {{{ $file->name }}}
  </a>

@endif
