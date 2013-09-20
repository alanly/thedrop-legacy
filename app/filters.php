<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	if (Sentry::check()) {
        $user = User::find(Sentry::getUser()->id);

        // Determine a count of undownloaded files.
        $downloadedIds = $user->downloads()->groupBy('file_id')->get(array('file_id'))->toArray();
        $downloadedIds = array_map(function($e) {return $e['file_id'];}, $downloadedIds);
        $downloadedIds[] = 0;
        $undownloadedFileCount = RepositoryFile::whereNotIn('id', $downloadedIds)->count();
        Session::flash('undownloaded.files.count', $undownloadedFileCount);

        // Ensure there is a count of open requests for admins
        if ($user->isAdmin())
            Session::flash('open.request.count', FileRequest::where('status', 0)->count());
    }

    if (! (Request::is('download/*') || Request::secure()))
        return Redirect::secure(Request::path());
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (! Sentry::check()) return Redirect::guest('auth/login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Sentry::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
 * Admin Filter
 * ----
 * The "admin" filter determines whether the user is part of the
 * "Administrative" user group. If they do not, then they are redirected
 * to the root route.
 *
 */

Route::filter('admin', function()
{

    if (! Sentry::getUser()->isAdmin())
        App::abort('401', 'You are not authorized to access this part of the application.');

});

/**
 * Privileged Filter
 * ----
 * This filter ensures that the particular route is only accessible to
 * privileged users. Users who aren't are presented with a message
 * explaining their circumstances.
 */

Route::filter('privileged', function()
{
    if (! Sentry::getUser()->isPrivileged()) {
        return View::make('layouts.privileged_only');
    }
});
