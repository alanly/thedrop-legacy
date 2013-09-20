<?php

class AdminFileRequestController extends \BaseController {

	protected $layout = 'admin/request/master';
	protected $user_id = null;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$requests = FileRequest::paginate(15);
		$requestsCount = FileRequest::count();

		$this->layout->content = View::make('admin/request/index')
									->with('requests', $requests)
									->with('requestsCount', $requestsCount);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$request = FileRequest::with('user', 'file')->find($id);

		if(! $request) return App::abort(404, "Request resource [$id] not found.");

		$this->layout->content = View::make('admin/request/show')->with('request', $request);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$request = FileRequest::with('user', 'file')->find($id);

		if(! $request) return App::abort(404, "Request resource [$id] not found.");

		$files = RepositoryFile::all();

		$filesArray = array();

		foreach($files as $file)
			$filesArray[$file->id] = $file->name;

		$this->layout->content = View::make('admin.request.edit')
									->with('request', $request)
									->with('filesArray', $filesArray);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if (! $this->user_id)
			$request = FileRequest::find($id);
		else
			$request = FileRequest::where('user_id', $this->user_id)->where('id', $id)->first();

		if (! $request) App::abort(404, "Request resource [$id] not found.");

		if ($this->user_id && $request->status != 0) {
			Session::flash('actionFlash.status', 'warning');
			Session::flash('actionFlash.message', Lang::get("requests/messages.edit.request_not_open"));
			return Redirect::back();
		}

		$rules = array(
				'title' => 'required',
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) return Redirect::back()->withErrors($validator)->withInput();

		if (! $this->user_id) {
			$request->status = Input::get('status');
			$request->admin_notes = Input::get('admin_notes');

			if (Input::get('status') > 0) {
				$request->file_id = Input::get('file_id');
				$request->fulfilled_on = new DateTime;
			} else {
				$request->file_id = null;
				$request->fulfilled_on = null;
			}
		} else {
			$request->title = Input::get('title');
			$request->user_notes = Input::get('user_notes');
		}

		$status = $request->save() ? 'success' : 'error';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/request/messages.update.{$status}"));

		return $this->show($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if (! $this->user_id)
			$request = FileRequest::find($id);
		else
			$request = FileRequest::where('user_id', $this->user_id)->where('id', $id)->first();

		if (! $request) App::abort(404, "Request resource [$id] not found.");

		$status = $request->delete() ? 'success' : 'error';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/request/messages.destroy.{$status}"));

		return $this->index();
	}

}