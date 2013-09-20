<?php

class TraktMetabaseDriver implements MetabaseDriverInterface
{
  private $supportedMedia = array('television', 'movie');
  private $traktApiBaseUrl = "http://api.trakt.tv/";
  private $traktApiKey = "1edc74874f676ae6c67b22ebdc511f35";

  public function getSupportedMedia()
  {
    return $this->supportedMedia;
  }

  public function isMediaSupported($mediaType)
  {
    return in_array(strtolower($mediaType), $this->supportedMedia);
  }

  public function getMetadata($mediaType, $identifiers)
  {
    $mediaType = strtolower($mediaType);

    if (! $this->isMediaSupported($mediaType))
      throw new UnsupportedMediaException("[{$mediaType}] is an unsupported media for the Trakt database.");

    if ($mediaType == 'television')
      return getShow($identifiers);

    if ($mediaType == 'movie')
      return getMovie($identifiers);
  }

  protected function getShow($identifiers)
  {
    if (! ( array_key_exists('title', $identifiers) || array_key_exists('slug', $identifiers) ) )
      throw new UnindentifiedMediaException("Show title is missing.");

    if (! array_key_exists('season', $identifiers))
      throw new UnindentifiedMediaException("Show season is missing.");

    if (! array_key_exists('episode', $identifiers))
      throw new UnindentifiedMediaException("Show episode is missing.");

  }

  protected function getMovie($identifiers)
  {
    if (! ( array_key_exists('title', $identifiers) || array_key_exists('slug', $identifiers) ) )
      throw new UnindentifiedMediaException("Movie title is missing.");

    if (! array_key_exists('year', $identifiers))
      throw new UnindentifiedMediaException("Movie release year is missing.");

    $slug = $this->determineMovieSlug($identifiers);

    return $this->queryTraktApi(
        array('movie', 'summary'),
        array($slug)
      );
  }

  public function determineTelevisionSlug($identifiers)
  {
    if (array_key_exists('slug', $identifiers)) return $identifiers['slug'];

    if (! array_key_exists('title', $identifiers))
      throw new UnindentifiedMediaException("Show title is missing.");

    /* Format the search query. */
    $searchQuery = urlencode(preg_replace('/[^\w\s]/', '', $identifiers['title']));

    /* Perform search and retrieve results. */
    $searchResults = $this->queryTraktApi(
        array('search', 'shows'),
        array($searchQuery)
      );

    /* Retrieve the first matching result. */
    $match = $searchResults[0];

    /* Retrieve the URL from the result, containing the slug. */
    $url = $match->url;

    /* Extract the slug from the URL and return. */
    return substr($url, strrpos($url, '/') + 1);
  }

  public function determineMovieSlug($identifiers)
  {
    if (array_key_exists('slug', $identifiers)) return $identifiers['slug'];

    if (! array_key_exists('title', $identifiers))
      throw new UnindentifiedMediaException("Movie title is missing.");

    $query = preg_replace('/[^\w\s]/', '', $identifiers['title']);

    if (array_key_exists('year', $identifiers))
      $query .= " " . $identifiers['year'];

    $query = urlencode($query);

    $results = $this->queryTraktApi(
        array('search', 'movies'),
        array($query)
      );

    /* Get the first result since that it our most likely match.
     * There's no science behind this, just purely assuming that the top result
     * will contain what we want.
     */
    $match = $results[0];

    /* Determine the slug based on the URL. */
    $url = $match->url;

    return substr($url, strrpos($url, '/') + 1);
  }

  private function queryTraktApi($query, $parameters)
  {
    $requestUrl = $this->traktApiBaseUrl . implode('/', $query) . ".json/{$this->traktApiKey}/" . implode('/', $parameters);

    if (Config::get('app.debug', false))
      Log::info("TraktMetabaseDriver: Request URL set to [{$requestUrl}]. Querying...");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $requestUrl,
        CURLOPT_RETURNTRANSFER => true,
      ));

    $response = curl_exec($ch);

    $curlErrNo = curl_errno($ch);

    if (Config::get('app.debug', false))
      Log::info("TraktMetabaseDriver: Request completed with exit status [{$curlErrNo}].");

    curl_close($ch)

    if ($response === false)
      throw new MetabaseRemoteRequestException("[{$curlErrNo}] Unable to retrieve a response from Trakt.tv.");

    $decodedResponse = json_decode($response);

    if (property_exists($decodedResponse, 'error'))
      throw new MetabaseRemoteRequestException("Trakt.tv returned error: " . $decodedResponse->error);

    return $decodedResponse;
  }
}