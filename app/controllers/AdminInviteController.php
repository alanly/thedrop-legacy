<?php

class AdminInviteController extends \BaseController {

	protected $layout = "admin.invite.master";

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		// Retrieve the filter values
		$inputResults = Input::get('results', 10);
		$inputStatus = Input::get('status', 'all');
		$inputInviter = Input::get('inviter', 0);

		// Retrieve count values of invites
		$openInviteCount = Invite::whereNull('invited_id')->count();
		$closedInviteCount = Invite::whereNotNull('invited_id')->count();
		$totalInviteCount = $openInviteCount + $closedInviteCount;

		// Create invite query
		$invites = null;

		/* Handle filter selections */

		if ($inputStatus === 'all' && $inputInviter == 0) {

			$invites = Invite::paginate($inputResults);

		} else {

			if ($inputStatus != 'all') {
				if ($inputStatus == 'open')
					$invites = Invite::whereNull('invited_id');
				else
					$invites = Invite::whereNotNull('invited_id');
			}

			if ($inputInviter != 0) {
				if ($inputStatus != 'all')
					$invites = $invites->Where('inviter_id', $inputInviter);
				else
					$invites = Invite::where('inviter_id', $inputInviter);
			}

			$invites = $invites->paginate($inputResults);

		}


		// Retrieve a list of all users
		$users = User::all();

		// Flash old input
		Input::flashOnly('status', 'inviter', 'results');

		$this->layout->content = View::make('admin.invite.index')
									->with('invites', $invites)
									->with('openInviteCount', $openInviteCount)
									->with('closedInviteCount', $closedInviteCount)
									->with('totalInviteCount', $totalInviteCount)
									->with('users', $users);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		$users = User::all();
		
		$this->layout->content = View::make('admin.invite.create')
									->with('users', $users);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		// Validation rules
		$rules = array(
				'amount' => 'required|numeric|min:1',
				'inviter' => 'required|exists:users,id',
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			Session::flash('actionFlash.status', 'error');
			Session::flash('actionFlash.message', Lang::get("admin/invite/messages.store.validator"));
			return Redirect::back();
			
		}

		try {

			$user = Sentry::getUserProvider()->findById(Input::get('inviter'));
			$amount = Input::get('amount');
			$successCount = 0;

			for ($i = 0; $i < $amount; $i++) {
				$invite = new Invite;
				
				$invite->inviter_id = $user->id;
				$invite->code = Invite::getRandomString();

				if ($invite->save()) $successCount++;
			}

			$status = ($successCount == $amount) ? 'success' : 'error';

			Session::flash('actionFlash.status', $status);
			Session::flash('actionFlash.message', Lang::get("admin/invite/messages.store.{$status}", array('amount' => $successCount, 'userName' => $user->name)));
			return Redirect::action('AdminInviteController@index');

		} catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

			App::abort(404, 'User not found');

		}

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		$invite = Invite::find($id);

		if (! $invite) {

			Session::flash('actionFlash.status', 'error');
			Session::flash('actionFlash.message', Lang::get("admin/invite/messages.destroy.not_found"));
			return Redirect::back();

		}

		if ($invite->isUsed()) {

			Session::flash('actionFlash.status', 'error');
			Session::flash('actionFlash.message', Lang::get("admin/invite/messages.destroy.is_used"));
			return Redirect::back();

		}

		$invite->delete();

		// Determine the status by checking if the invite still exists
		$status = (! Invite::find($id)) ? 'success' : 'error';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/invite/messages.destroy.{$status}"));
		return Redirect::back();

	}

}