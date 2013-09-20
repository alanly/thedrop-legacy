<?php

class ProfileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{

		$user = Sentry::getUser();
		
		return View::make('my.profile.index')
					->with('user', $user);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postIndex()
	{
		
		$rules = array(
				'name' => 'required',
				'email' => 'required|email',
				'password' => 'required',
				'new_password' => 'min:8|confirmed',
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
			return Redirect::back()->withInput()->withErrors($validator);

		$user = Sentry::getUser();

		if (! $user->checkPassword(Input::get('password'))) {
			$this->messageBag->add('password', Lang::get("profile/messages.incorrect_password"));
			return Redirect::back()->withInput()->withErrors($this->messageBag);
		}

		// Update name and email
		$user->name = Input::get('name');
		$user->email = Input::get('email');

		// Update the password if necessary
		if (Input::has('new_password'))
			$user->password = Input::get('new_password');

		$status = $user->save() ? 'success' : 'error';

		// Reset the API key if necessary
		if (Input::has('reset_api_key'))
			$user->resetApiKey();

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("profile/messages.{$status}"));

		return Redirect::back();

	}

}