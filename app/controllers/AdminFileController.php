<?php

class AdminFileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$files = RepositoryFile::with('downloads')->get();
		$totalFiles = count($files);
		$totalSpaceOccupied = RepositoryFile::sum('size');

		return View::make('admin.file.index')
					->with('files', $files)
					->with('totalFiles', $totalFiles)
					->with('totalSpaceOccupied', $totalSpaceOccupied);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		if (Input::get('action') == 'deleteMultiple') {
			return $this->destroyMultiple(Input::get('selectedFiles'));
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{	
		$file = RepositoryFile::with('downloads.user', 'metadata')->find($id);
		$downloads = $file->downloads()->groupBy('user_id')->get();

		if (! $file) return App::abort(404, 'File not found.');

		return View::make('admin.file.show')->with('file', $file)->with('downloads', $downloads);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$file = RepositoryFile::find($id);
		$action = Input::get('action');

		if (! $file) return App::abort(404, 'File not found.');
		
		if ($action == 'autoMetadata') {

			$file->refreshMetadata();

			Session::flash('actionFlash.status', 'info');
			Session::flash('actionFlash.message', Lang::get('admin/files/messages.update.success'));

			return Redirect::back();

		}

		if ($action == 'manualMetadata') {

			$traktData = array(
					'slug' => Input::get('slug'),
					'season' => Input::get('season'),
					'episode' => Input::get('episode'),
					'quality' => Input::get('quality'),
				);

			$fileType = Input::get('type');

			$file->type = $fileType;
			$file->save();

			$file->manualRefreshMetadata($fileType, $traktData);

			Session::flash('actionFlash.status', 'info');
			Session::flash('actionFlash.message', Lang::get('admin/files/messages.update.success'));

			return Redirect::action('AdminFileController@show', array('file' => $id));

		}

		return App::abort(404, "Command [{$action}] not found.");

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
		$file = RepositoryFile::find($id);

		if (! $file) App::abort(404, 'File not found.');

		$filename = $file->name;
		$status = $file->delete() ? 'success' : 'error';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/files/messages.destroy.{$status}", array('filename' => $filename)));

		Cache::forget('files');

		if (Request::is('/manage/file/*'))
			return Redirect::action('AdminFileController@index');

		return Redirect::back();

	}

	public function destroyMultiple($ids)
	{
		$hasError = true;

		foreach (RepositoryFile::whereIn('id', $ids)->get() as $file) {
			$hasError = $hasError && $file->delete();
		}

		$status = $hasError ? 'success' : 'error';

		Session::flash('actionFlash.status', $status);
		Session::flash('actionFlash.message', Lang::get("admin/files/messages.destroyMultiple.{$status}"));

		Cache::forget('files');

		return Redirect::back();
	}

}