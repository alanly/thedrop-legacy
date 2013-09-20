<?php

class DownloadsController extends \BaseController
{

    protected $layout = 'my.downloads.master';

    public function getIndex()
    {
        return Redirect::action('DownloadsController@getStatistics');
    }

    public function getStatistics()
    {
        $user = Sentry::getUser();
        $periodStart = Input::get('periodStart', date('Y-m-d', time() - 604800));
        $periodEnd = Input::get('periodEnd', date('Y-m-d'));

        $dateStart = (new DateTime($periodStart))->setTime(0,0,0);
        $dateEnd = (new DateTime($periodEnd))->setTime(23,59,59);
        $daysDiff = $dateStart->diff($dateEnd)->days;

        $downloadsPerDay = array();

        for ($i = 0; $i <= $daysDiff; $i++) {
            $downloadsPerDay[$dateStart->format('Y-m-d T')] = $user->downloads()
                                                                ->whereBetween('created_at', array(
                                                                        $dateStart->format('Y-m-d G:i:s'),
                                                                        $dateStart->modify('next day')->format('Y-m-d G:i:s'),
                                                                    ))
                                                                ->count();
        }

        $this->layout->content = View::make('my.downloads.statistics')
                                    ->with('periodStart', $periodStart)
                                    ->with('periodEnd', $periodEnd)
                                    ->with('downloadsPerDay', $downloadsPerDay);
    }

    public function getListing()
    {
        $user = Sentry::getUser();
        $dataOrder = Input::get('orderIn', 'desc');
        $dataRows = Input::get('results', 10);

        Input::flash();

        $downloads = $user->downloads()->orderBy('id', ($dataOrder == 'desc' ? 'desc' : 'asc'))->paginate($dataRows);
        $totalDownloads = $user->downloads()->count();

        $this->layout->content = View::make('my.downloads.listing')
                                    ->with('totalDownloads', $totalDownloads)
                                    ->with('downloads', $downloads);
    }

}
