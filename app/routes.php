<?php

/*
 * API Routes
 */

Route::group(array('domain' => 'api.thedrop.pw'), function() {

    // Management routes
    Route::group(array('prefix' => 'manage'), function() {
        // Force update file listings.
        Route::get('update-files/{key}', 'ApiController@getUpdateFiles');
    });

    Route::group(array('prefix' => 'v1'), function() {

        // Download route
        Route::get('download/{key}/{filename}', array('uses' => 'FilesController@getDownload'));
        
        // File listing route
        Route::get('files/{key}', 'ApiController@getFiles');

        // Format Route
        Route::get('format.{form}/{key}', 'ApiController@getFormat');

    });

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('before' => 'auth', function()
{
	return Redirect::route('files');
}));


/*
 * Authentication Routes
 */

Route::group(array('prefix' => 'auth'), function() {

    // Login page routes
    Route::get('login', array('before' => 'guest', 'as' => 'login', 'uses' => 'AuthController@getLogin'));
    Route::post('login', array('before' => 'guest', 'uses' => 'AuthController@postLogin'));

    // Register page routes
    Route::get('register', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@getRegister'));
    Route::post('register', array('before' => 'guest', 'uses' => 'AuthController@postRegister'));

    // Logout route
    Route::get('logout', array('before' => 'auth', 'as' => 'logout', 'uses' => 'AuthController@getLogout'));

    // Account activation route
    Route::get('activate', array('before' => 'guest', 'as' => 'activate', 'uses' => 'AuthController@getActivate'));

    // Password reset route
    Route::get('reset', array('before' => 'guest', 'as' => 'reset', 'uses' => 'AuthController@getReset'));
    Route::post('reset', array('before' => 'guest', 'uses' => 'AuthController@postReset'));
    Route::get('reset/confirm', array('before' => 'guest', 'as' => 'reset.confirm', 'uses' => 'AuthController@getResetConfirm'));
    Route::post('reset/confirm', array('before' => 'guest', 'uses' => 'AuthController@postResetConfirm'));

});

/*
 * Administration Routes
 */

Route::group(array('before' => 'auth|admin', 'prefix' => 'manage'), function() {

    // User administration routes
    Route::resource('user', 'AdminUserController');

    // Invite administration route
    Route::resource('invite', 'AdminInviteController');

    // Files administration route
    Route::resource('file', 'AdminFileController');

    // Request administration route
    Route::resource('request', 'AdminFileRequestController');

    // Statistics administration route
    Route::controller('statistics', 'AdminDownloadStatisticsController');

});

/*
 * Personal account related routes
 */

Route::group(array('before' => 'auth', 'prefix' => 'my'), function() {

    // My Profile
    Route::get('profile', array('as' => 'profile', 'uses' => 'ProfileController@getIndex'));
    Route::post('profile', 'ProfileController@postIndex');

    // My Invites
    Route::get('invites', array('as' => 'invites', 'uses' => 'InvitesController@getIndex'));

    // My Downloads
    Route::controller('downloads', 'DownloadsController');

    // My Requests
    Route::resource('requests', 'FileRequestController');

    // My Settings
    Route::controller('settings', 'MySettingsController');

});

/*
 * General application routes
 */

    // Files
    Route::get('files', array('as' => 'files', 'before' => 'auth', 'uses' => 'FilesController@getIndex'));

    // Download
    Route::get('download/{key}/{filename}', array('as' => 'download', 'uses' => 'FilesController@getDownload'));

    // Schedule
    Route::get('schedule', array('as' => 'schedule', 'before' => 'auth', function() {
        $calendarSource = 'bug92sm7sf5i6sgcusb6smeqcdmqua0v%40import.calendar.google.com';
        return View::make('schedule')->with('src', $calendarSource);
    }));

    // Donate
    Route::get('donate', array('as' => 'donate', 'before' => 'auth', function() {
        return View::make('donate');
    }));

    // Chrome Button
    Route::get('chromeapp', array('as' => 'chromeapp', 'before' => 'auth', function() {
        return View::make('chromeapp');
    }));

    // Show List
    Route::get('shows', array('as' => 'shows', 'before' => 'auth', function() {
        $shows = Cache::remember('show.list', 1440, function() {
            // Define Trakt API url to use for show list
            $listUrl = 'http://api.trakt.tv/user/list.json/1edc74874f676ae6c67b22ebdc511f35/alanly/tracked-tv-shows';

            // Retrieve listing from Trakt.tv
            try {
                $list = json_decode(file_get_contents($listUrl));
            } catch (Exception $e) {
                return;
            }

            // Create array to hold shows.
            $shows = array();

            // Process results from Trakt
            foreach ($list->items as $item)
                if ($item->type === 'show')
                    $shows[] = $item->show;

            // Sort shows in order by name
            uasort($shows, function($a, $b) {
                return strcasecmp($a->title, $b->title);
            });

            return $shows;
        });

        return View::make('shows')->with('shows', $shows);
    }));
