<?php

interface MetabaseDriverInterface
{
  /**
   * Returns a list of supported media types for this metadata-database.
   * i.e. 'television', 'movie', 'music', etc.
   */
  public function getSupportedMedia();

  /**
   * Retrieves the metadata for a media of type `$mediaType` identified via `$identifiers`.
   */
  public function getMetadata($mediaType, $identifiers);
}