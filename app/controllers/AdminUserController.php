<?php

class AdminUserController extends \BaseController {

	protected $layout = 'admin.user.master';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $users = Sentry::getUserProvider()->findAll();

        $this->layout->content = View::make('admin.user.list')->with('users', $users);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

		$user = $this->getUser($id);

		// Retrieve filter values
		$inputSort = Input::get('sort', 'desc');
		$inputResults = Input::get('results', 10);

		$downloads = $user->downloads()->orderBy('id', ($inputSort == 'desc' ? 'desc' : 'asc'))->paginate($inputResults);

		// Generate download statistics
		$downloadStats = array(
				'this.month' => $user->downloads()
									->whereBetween('created_at', array(
											(new DateTime)->setTime(0,0,0)->modify('first day of this month'),
											(new DateTime)->setTime(0,0,0)->modify('first day of next month'),
										))
									->distinct()
									->count('file_id'),
				'last.month' => $user->downloads()
									->whereBetween('created_at', array(
											(new DateTime)->setTime(0,0,0)->modify('first day of last month'),
											(new DateTime)->setTime(0,0,0)->modify('first day of this month'),
										))
									->distinct()
									->count('file_id'),
				'this.year' => $user->downloads()
									->whereBetween('created_at', array(
											(new DateTime)->setTime(0,0,0)->modify('first day of january'),
											(new DateTime)->setTime(0,0,0)->modify('first day of january next year'),
										))
									->distinct()
									->count('file_id'),
				'lifetime' => $user->downloads()->distinct()->count('file_id'),
			);

		$this->layout->content = View::make('admin.user.show')
			->with('user', $user)
			->with('invites', $user->invites)
			->with('downloads', $downloads)
			->with('downloadStats', $downloadStats);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		$user = $this->getUser($id);
		$isDeletable = !($id == 1 || $id == Sentry::getUser()->id);
		$groups = Sentry::getGroupProvider()->findAll();
		
		$this->layout->content = View::make('admin.user.edit')
			->with('user', $user)
			->with('isDeletable', $isDeletable)
			->with('groups', $groups);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$user = $this->getUser($id);

		$updateType = Input::get('_updateType');

		// Handle a request to reset the API key.		
		if ($updateType == 'reset.key') {

			$status = (! $user->resetApiKey()) ? 'error' : 'success';

			Session::flash('actionFlash.status', $status);
			Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.api_key.{$status}", array('name' => $user->name)));

			return Redirect::back();

		}

		// Handle a request to add the user to a group.
		if ($updateType == 'add.group') {

			$groupId = Input::get('group');

			if(! ctype_digit($groupId)) {
				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.unspecified_group"));
				return Redirect::back();
			}

			try {

				$group = Sentry::getGroupProvider()->findById($groupId);

				if($user->inGroup($group)) {
					Session::flash('actionFlash.status', 'info');
					Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.add.already_in_group", array('userName' => $user->name, 'groupName' => $group->name)));
					return Redirect::back();
				}

				$status = $user->addGroup($group) ? 'success' : 'error';

				Session::flash('actionFlash.status', $status);
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.add.{$status}", array('userName' => $user->name, 'groupName' => $group->name)));

				return Redirect::back();

			} catch (GroupNotFoundException $e) {

				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.group_not_found"));
				return Redirect::back();

			}

		}

		// Handle a request to remove the user from a group.
		if ($updateType == 'remove.group') {

			$groupId = Input::get('_groupId');

			if (! ctype_digit($groupId)) {
				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.unspecified_group"));
				return Redirect::back();
			}

			try {

				$group = Sentry::getGroupProvider()->findById($groupId);

				if ($group->name == "Administrators" && $user->id == 1) {
					Session::flash('actionFlash.status', 'error');
					Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.remove.primary_admin_error"));
					return Redirect::back();
				}

				if (! $user->inGroup($group)) {
					Session::flash('actionFlash.status', 'info');
					Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.remove.not_in_group", array('userName' => $user->name, 'groupName' => $group->name)));
					return Redirect::back();
				}

				$status = $user->removeGroup($group) ? 'success' : 'error';

				Session::flash('actionFlash.status', $status);
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.remove.{$status}", array('userName' => $user->name, 'groupName' => $group->name)));

				return Redirect::back();

			} catch (GroupNotFoundException $e) {

				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.group.group_not_found"));
				return Redirect::back();

			}

		}

		// Handle a request to toggle the user's ban/lock status.
		if ($updateType == 'toggle.ban') {

			if ($user->id == 1) {
				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.ban.primary_admin_error"));
				return Redirect::back();
			}

			if ($user->id == Sentry::getUser()->id) {
				Session::flash('actionFlash.status', 'error');
				Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.ban.current_user_error"));
				return Redirect::back();
			}

			$throttle = Sentry::getThrottleProvider()->findByUserId($user->id);
			$isBanned = $throttle->isBanned();

			if ($isBanned)
				$throttle->unban();
			else
				$throttle->ban();

			$status = ($isBanned !== $throttle->isBanned()) ? 'success' : 'error';

			Session::flash('actionFlash.status', $status);
			Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.ban.{$status}", array('userName' => $user->name)));

			return Redirect::back();

		}

		// Catch all other Update requests.
		Session::flash('actionFlash.status', 'error');
		Session::flash('actionFlash.message', Lang::get("admin/user/messages.update.undefined_update_type"));
		return Redirect::back();

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		if ($id == Sentry::getUser()->id) {
			Session::flash('actionFlash.status', 'error');
			Session::flash('actionFlash.message', Lang::get("admin/user/messages.destroy.current_user_error"));
			return Redirect::back();
		}
		
		if ($id == 1) {
			Session::flash('actionFlash.status', 'error');
			Session::flash('actionFlash.message', Lang::get("admin/user/messages.destroy.primary_admin_error"));
			return Redirect::back();
		}

		$user = $this->getUser($id);

		$name = $user->name;
		$status = (! $user->delete()) ? 'error' : 'success';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/user/messages.destroy.{$status}", array('name' => $name)));

		return Redirect::action('AdminUserController@index');

	}

	private function getUser($id)
	{

		try {

			$user = User::find($id);

			if (! $user)
				throw new Cartalyst\Sentry\Users\UserNotFoundException;

			return $user;

		} catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

			App::abort(404, 'User not found.');

		}

	}

}
