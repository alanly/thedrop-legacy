<?php

class FileRequestController extends AdminFileRequestController {

	public function __construct()
	{
		$this->beforeFilter('privileged');

		$this->layout = 'my.requests.master';
		$this->user_id = Sentry::getUser()->id;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user = Sentry::getUser();
		$requests = $user->requests()->get();
		$requestsCount = $user->requests()->count();

		$this->layout->content = View::make('my.requests.index')
									->with('requests', $requests)
									->with('requestsCount', $requestsCount);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout->content = View::make('my.requests.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
				'title' => 'required'
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) return Redirect::back()->withErrors($validator)->withInput();

		$request = FileRequest::create(array(
				'user_id' => Sentry::getUser()->id,
				'status' => 0,
				'title' => Input::get('title'),
				'user_notes' => Input::get('notes'),
			));

		$status = (! $request) ? 'error' : 'success';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("requests/messages.store.{$status}"));

		return Redirect::action('FileRequestController@index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = Sentry::getUser();
		$request = $user->requests()->with('file')->where('id', $id)->first();

		if (! $request) return App::abort(404, "Request resource [$id] not found.");

		$file = $request->file()->first();

		$this->layout->content = View::make('my.requests.show')
									->with('user', $user)
									->with('request', $request)
									->with('file', $file);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = Sentry::getUser();
		$request = $user->requests()->where('id', $id)->first();

		if (! $request) return App::abort(404, "Request resource [$id] not found.");

		if ($request->status != 0) {
			Session::flash('actionFlash.status', 'warning');
			Session::flash('actionFlash.message', Lang::get("requests/messages.edit.request_not_open"));
			return Redirect::back();
		}

		$this->layout->content = View::make('my.requests.edit')
									->with('request', $request);
	}

}