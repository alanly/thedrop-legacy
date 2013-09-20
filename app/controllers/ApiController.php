<?php

class ApiController extends \BaseController
{

    private $fileListingCacheTime = 10;

    public function getFiles($key)
    {

        $user = $this->getApiUserByKey($key);

        // Retrieve the file listing from the cache
        $files = Cache::remember('files', $this->fileListingCacheTime, function()
            {
                return RepositoryFile::with('metadata')->select()->orderBy('modified_at', 'desc')->get();
            });

        $response = array();

        foreach ($files as $file) {
            $response[] = array(
                    'id' => $file->id,
                    'type' => $file->getMediaType(),
                    'filename' => $file->name,
                    'size' => $file->size,
                    'modified_at' => $file->modified_at,
                    'link' => URL::action('FilesController@getDownload', array('key' => $key, 'filename' => $file->name)),
                );
        }

        return Response::json($response);

    }

    public function getUpdateFiles($key)
    {

        $user = $this->getApiUserByKey($key);

        if (! $user->isAdmin()) App::abort(401, 'You are not authorized.');

        RepositoryFile::updateFileListing();

        return Response::json(array('status' => 'completed'));
        
    }

    private function getApiUserByKey($key)
    {

        $user = User::where('api_key', $key)->first();

        if (! $user || ! $user->isPrivileged()) App::abort(401, 'You are not authorized.');

        return $user;

    }

    public function getFormat($form = 'xhtml')
    {
        return Response::json(array('format' => $form));
    }

}
