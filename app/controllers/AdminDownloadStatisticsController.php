<?php

class AdminDownloadStatisticsController extends \BaseController
{
    private $periodStart = null;
    private $periodEnd = null;

    public function __construct()
    {
        $this->determineAnalysisPeriod();
    }

    public function getIndex()
    {
        return View::make('admin.downloadStatistics.index')
            ->with('statistics', $this->getStatistics())
            ->with('periodStart', $this->periodStart)
            ->with('periodEnd', $this->periodEnd);
    }

    public function getGraph()
    {
        //
    }

    public function getListing()
    {
        $dataOrder = Input::get('order', 'desc') == 'desc' ? 'desc' : 'asc';
        $dataCount = Input::get('count', 10);

        // Flash order and count input to next get.
        Input::flash();

        $downloads = Download::orderBy('id', $dataOrder)->paginate($dataCount);
        $totalCount = Download::count();

        return View::make('admin.downloadStatistics.log')
            ->with('downloads', $downloads)
            ->with('totalCount', $totalCount);
    }

    private function determineAnalysisPeriod() {
        // Retrieve the period from input if available, otherwise default to the last week.
        $periodStart = Input::get('periodStart', '1 month ago today');
        $periodEnd = Input::get('periodEnd', 'today');

        // Generate DateTime objects and set time appropriately.
        $periodStart = (new DateTime($periodStart))->setTime(0,0,0);
        $periodEnd = (new DateTime($periodEnd))->setTime(23,59,59);

        // Store results for request
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
    }

    private function getStatistics() {
        // Declare array to hold our results in
        $statistics = array(
            'downloadCount' => null,
            'bandwidthUsage' => null,
            'mostDownloadedFile' => null,
            'mostActiveDownloader' => null,
        );

        // Retrieve the distinct downloads in this period.
        $distinctDownloads = Download::distinct()
            ->select('file_id', 'user_id', 'ip_address')
            ->whereBetween('created_at', array(
                    $this->periodStart->format('Y-m-d G:i:s'),
                    $this->periodEnd->format('Y-m-d G:i:s')
                ))
            ->remember(30)
            ->get();

        // Determine the download count.
        $statistics['downloadCount'] = count($distinctDownloads);

        $totalBandwidth = 0;
        $files = array();
        $users = array();

        foreach ($distinctDownloads as $download) {
            $file = $download->file()->first();
            $totalBandwidth += $file->size;

            if (array_key_exists($file->id, $files))
                $files[$file->id]++;
            else
                $files[$file->id] = 1;

            if (array_key_exists($download->user_id, $users))
                $users[$download->user_id]++;
            else
                $users[$download->user_id] = 1;
        }

        $statistics['bandwidthUsage'] = RepositoryFile::generatePrettySize($totalBandwidth);

        arsort($files);
        $mostDownloadedFile = array_keys($files)[0];

        arsort($users);
        $mostActiveDownloader = array_keys($users)[0];

        $statistics['mostDownloadedFile'] = RepositoryFile::withTrashed()->find($mostDownloadedFile)->name;
        $statistics['mostActiveDownloader'] = link_to_action('AdminUserController@show', User::find($mostActiveDownloader)->name, array('user' => $mostActiveDownloader));

        // Return results
        return $statistics;
    }
}
