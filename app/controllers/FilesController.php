<?php

class FilesController extends \BaseController
{

    protected $sendFilePath = '/repos/';

    protected $fileListingCacheTime = 10;

    public function getIndex()
    {

        // Retrieve the file listing from the cache
        $files = Cache::remember('files', $this->fileListingCacheTime, function()
            {
                return RepositoryFile::with('metadata')->select()->orderBy('modified_at', 'desc')->get();
            });

        return View::make('files')
                    ->with('files', $files)
                    ->with('user', Sentry::getUser());

    }

    public function getDownload($key, $filename)
    {

        // Check if the API key corresponds to a user.
        $user = User::where('api_key', $key)->first();
        if (! $user) App::abort(401, 'You are unauthorized.');

        // Check if the filename corresponds to a file.
        $file = RepositoryFile::where('name', $filename)->first();
        if (! $file) App::abort(404, 'File not found.');

        // Create the download record
        $this->createDownloadRecord($user, $file);

        // Respond with the download.
        return Response::download(
            $file->path,
            $file->getPrettyFilename(),
            array(
                    'X-Accel-Redirect' => $this->sendFilePath . $file->name,
                )
            );

    }

    private function createDownloadRecord($user, $file)
    {

        /**
         * We will now create the download record, but first we will be
         * throttling the amount of records created in case the user
         * is using parallel/concurrent downloads.
         * The key attributes to determine whether or not a record
         * should be throttled is primarily based on two variables:
         *      - last created_at time,
         *      - ip address.
         */

        $remoteAddr = Request::server('REMOTE_ADDR');

        /* Check for records within the last 5 minutes with the same
         * filename and IP address.
         */

        $records = Download::where('created_at', '>', (new DateTime)->setTimestamp(time() - 300))
                        ->where('file_id', $file->id)
                        ->where('ip_address', $remoteAddr)
                        ->count();

        if ($records > 0) return;

        // If all is well, create the record and save it.
        $download = new Download(array(
                'file_id' => $file->id,
                'ip_address' => $remoteAddr,
            ));

        $user->downloads()->save($download);

    }

}
