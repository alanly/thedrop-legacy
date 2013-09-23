<?php

class RepositoryFile extends Eloquent
{

    protected $table = 'files';

    protected $softDelete = true;

    protected $fillable = array('name', 'size', 'path', 'modified_at', 'type');

    public static $PUBLIC_DIRECTORY = '';

    public static $WATCH_DIRECTORY = '/home/alan/repos/';

    /*
     * Overloaded Methods
     */

    public function delete()
    {

        if (! File::exists($this->path)) return false;

        $this->requests()->delete();

        return (File::delete($this->path) ? parent::delete() : false);

    }

    /*
     * Utility Methods
     */

    public function getPrettySize()
    {

        return self::generatePrettySize($this->size);

    }

    public function getExtension()
    {

        return pathinfo($this->path, PATHINFO_EXTENSION);

    }

    public function getIconType()
    {

        $iconMap = array(
                'audio.album' => 'music',
                'video.tv' => 'desktop',
                'video.movie' => 'film',
                'video.generic' => 'facetime-video',
                'file.text' => 'file-text',
                'file.archive' => 'archive',
                'file.unknown' => 'file',
            );

        $mediaType = $this->getMediaType();

        return $iconMap[$mediaType];

    }

    public function getMediaType()
    {

        return (! $this->type) ? static::determineMediaType($this->name) : $this->type;

    }

    public static function updateFileListing()
    {

        Cache::forget('files');

        $watchDirFiles = File::allFiles(static::$WATCH_DIRECTORY);

        foreach ($watchDirFiles as $file) {
            if (RepositoryFile::where('name', $file->getFilename())->count() == 0) {
                $repositoryFile = RepositoryFile::create(array(
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => static::determineSize($file->getPathname()),
                        'modified_at' => (new DateTime())->setTimestamp(File::lastModified($file->getPathname())),
                        'type' => static::determineMediaType($file->getFilename()),
                    ));

                MetadataParser::parseAndSave($repositoryFile);
            }
        }

    }

    public function refreshMetadata()
    {

        // Remove existing metadata.
        $this->metadata()->delete();

        // Retrieve up-to-date metadata on file.
        return MetadataParser::parseAndSave($this);

    }

    public function manualRefreshMetadata($type, $traktData = null)
    {
        if (! $type || is_null($traktData)) return $this->refreshMetadata();

        $this->metadata()->delete();

        MetadataParser::parse($this->id, $type, $traktData);
    }

    public static function determineSize($path)
    {

        if (! File::isFile($path)) return false;

        return exec('stat -c %s ' . escapeshellarg($path));

    }

    public static function generatePrettySize($bytes)
    {

        $formattedSize = $bytes;
        $units = array("B", "KiB", "MiB", "GiB", "TiB");
        $unitIndex = 0;

        while ($formattedSize > 1024) {
            $formattedSize /= 1024;
            ++$unitIndex;
        }

        $formattedSize = number_format($formattedSize, 2);

        return $formattedSize . ' ' . $units[$unitIndex];

    }

    public static function determineMediaType($filename)
    {

        $mediaTypes = array(
                'audio.album' => "@.+(MP3|FLAC).+\.(zip|tar)$@i",
                'video.tv' => "@.+(HDTV).+(x264|h264).+@i",
                'video.movie' => "@.+(\d{4}).+(720p|1080p).+@i",
                'video.generic' => "@.+(mkv|mp4|avi|ts|mpg|mpeg)$@i",
                'file.text' => "@.+(txt|pdf|ppt|doc)$@i",
                'file.archive' => "@.+(zip|tar|gz|rar|7zip)$@i",
                'file.unknown' => "@.+@",
            );

        foreach ($mediaTypes as $key => $value)
            if (preg_match($value, $filename) === 1)
                return $key;

    }

    public function getMetadata($key, $default = '')
    {

        $meta = $this->metadata()->where('key', $key)->first();

        return (! $meta) ? $default : $meta->value;

    }

    public function determinePrettyFilename()
    {
        $prettyFilename = $this->name;

        switch($this->type) {
            case "audio.album":
                $prettyFilename = $this->getMetadata('album.artist') . ' - ' . $this->getMetadata('album.title') . ' [' . $this->getMetadata('media.quality') . '].' . $this->getExtension();
                break;
            case "video.tv":
                $prettyFilename = $this->getMetadata('show.title');

                if ($season = $this->getMetadata('show.season')) {
                    $prettyFilename .= ' - s' . str_pad($season, 2, '0', STR_PAD_LEFT);

                    if ($episode = $this->getMetadata('season.episode'))
                        $prettyFilename .= 'e' . str_pad($episode, 2, '0', STR_PAD_LEFT);
                }

                if ($episodeTitle = $this->getMetadata('episode.title'))
                    $prettyFilename .= ' - ' . $episodeTitle;

                $prettyFilename .= ' (' . $this->getMetadata('media.quality') . ').' . $this->getExtension();

                break;
            case "video.movie":
                $prettyFilename = $this->getMetadata('movie.title') . ' (' . $this->getMetadata('movie.year') . ') - ' . $this->getMetadata('media.quality') . '.' . $this->getExtension();
                break;
        }

        $this->metadata()->save(new FileMeta(array('key' => 'pretty.filename', 'value' => $prettyFilename)));

        return $prettyFilename;
    }

    public function getPrettyFilename()
    {
        // Check if we already have a set pretty name, and if we do, just return that value.
        if ($prettyFilename = ($this->getMetadata('pretty.filename'))) return $prettyFilename;

        // Otherwise we will have to get it.
        return $this->determinePrettyFilename();        
    }

    /*
     * Model Relationships
     */

    public function downloads()
    {
        return $this->hasMany('Download', 'file_id');
    }

    public function metadata()
    {
        return $this->hasMany('FileMeta', 'file_id');
    }

    public function requests()
    {
        return $this->hasMany('FileRequest', 'file_id');
    }

}

class MetadataParser
{

    public static function parse($file_id, $type, $traktData = null)
    {

        $file = RepositoryFile::find($file_id);

        if (! $file) return App::abort(404, "File [{$file_id}] not found.");

        $metadata = null;

        if ($type == 'audio.album') $metadata = self::parseAlbum($file->name);
        if ($type == 'video.tv') $metadata = self::parseTv($file->name, $traktData);
        if ($type == 'video.movie') $metadata = self::parseMovie($file->name, $traktData);

        if (! $metadata) return false;

        foreach ($metadata as $key => $value) {
            if ($value != '') {
                FileMeta::create(array(
                        'file_id' => $file->id,
                        'key' => $key,
                        'value' => $value,
                    ));
            }
        }

        RepositoryFile::updateFileListing();

        return true;

    }

    public static function parseAndSave($file)
    {

        return self::parse($file->id, $file->type);

    }

    private static function parseTv($filename, $traktData = null)
    {

        $regex = array(
                "'^(.+)\.S([0-9]+)E([0-9]+).*\.(\d+p|i).+'i",
                "'^([a-z\.]+)\.([\d\.]+)\.(\d+p|i).+'i",
                "'^(.+)\.(\d+p|i).+'i",
            );

        $meta = array();

        foreach ($regex as $pattern) {
            if (preg_match($pattern, $filename, $matches) === 1) {
                $count = count($matches);
                
                $meta['show.title'] = ucwords(preg_replace("'\.'", " ", $matches[1]));

                if ($count == 5 || ! is_null($traktData)) {
                    if (! is_null($traktData)) {
                        $slug = @$traktData['slug'];
                        $season = @$traktData['season'];
                        $episode = @$traktData['episode'];
                        $quality = @$traktData['quality'];
                    } else {
                        $slug = $season = $episode = $quality = null;
                    }

                    if (! $slug) $slug = strtolower(preg_replace("@ @", "-", $meta['show.title']));
                    if (! $season) $season = (int) $matches[2];
                    if (! $episode) $episode = (int) $matches[3];
                    if (! $quality) $quality = $matches[4];

                    $meta['show.season'] = $season;
                    $meta['season.episode'] = $episode;
                    $meta['media.quality'] = $quality;

                    if ( ($traktSummary = self::getSummaryFromTrakt(array('show', 'episode'), array($slug, $season, $episode))) != false ) {
                        $meta['episode.title'] = $traktSummary->episode->title;
                        $meta['episode.overview'] = $traktSummary->episode->overview;
                        $meta['show.title'] = $traktSummary->show->title;
                        $meta['video.screenshot'] = preg_replace('(http:)', '', $traktSummary->episode->images->screen);
                    }

                    break;
                }

                if ($count == 4) {
                    $meta['season.episode'] = (int) $matches[2];
                    $meta['media.quality'] = $matches[3];
                }

                if ($count == 3) {
                    $meta['media.quality'] = $matches[2];
                }

                break;
            }
        }

        return $meta;

    }

    private static function parseMovie($filename, $traktData = null)
    {

        $regex = array(
                "@^(.+)\.(\d{4}).+(720p|1080p).+@i",
            );

        $meta = array();

        foreach ($regex as $pattern) {
            if (preg_match($pattern, $filename, $matches) === 1 || ! is_null($traktData)) {
                if (count($matches) > 1) {
                    $meta['movie.title'] = ucwords(preg_replace("'\.'", " ", $matches[1]));
                    $meta['movie.year'] = $matches[2];
                    $meta['media.quality'] = $matches[3];
                }

                if (! is_null($traktData)) $meta['media.quality'] = $traktData['quality'];

                $slug = (is_null($traktData)) ? strtolower(preg_replace("@ @", "-", $meta['movie.title'])) . '-' . $meta['movie.year'] : $traktData['slug'];

                if ( ($traktSummary = self::getSummaryFromTrakt(array('movie'), array($slug))) != false ) {
                    $meta['video.imdb.id'] = $traktSummary->imdb_id;
                    $meta['movie.tagline'] = $traktSummary->tagline;
                    $meta['movie.overview'] = $traktSummary->overview;
                    $meta['movie.title'] = $traktSummary->title;
                    $meta['movie.year'] = $traktSummary->year;
                    $meta['video.poster'] = preg_replace('(http:)', '', $traktSummary->images->poster); // Make the URL https compatible.
                }

                break;
            }
        }

        return $meta;

    }

    private static function parseAlbum($filename)
    {

        $regex = array(
                "@^(.+)-(.+).+(MP3|FLAC).+@i",
            );

        $meta = array();

        foreach ($regex as $pattern) {
            if (preg_match($pattern, $filename, $matches) === 1) {
                $meta['album.artist'] = ucwords(preg_replace("'\.'", " ", $matches[1]));
                $meta['album.title'] = ucwords(preg_replace("'\.'", " ", $matches[2]));
                $meta['media.quality'] = $matches[3];

                break;
            }
        }

        return $meta;

    }

    private static function getSummaryFromTrakt($type, $data)
    {

        $url = "http://api.trakt.tv/"
                . implode('/', $type) 
                . "/summary.json/"
                . Config::get('metadata.connections.trakt.key')
                . "/"
                . implode('/', $data);

        if (($response = @file_get_contents($url)) != false) {
            $summary = json_decode($response);

            if (! property_exists($summary, 'error'))
                return $summary;
        }

        return false;

    }

}
