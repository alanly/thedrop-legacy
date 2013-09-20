<?php

class MySettingsController extends \BaseController
{
    protected $layout = 'my.settings.master';

    /**
     * All of the possible setting attributes (keys) related to the background.
     */
    private $backgroundAttributes = array(
        'background.enable', 
        'background.color', 
        'background.image', 
        'background.attachment', 
        'background.position.x', 
        'background.position.y', 
        'background.repeat', 
        'background.size'
        );

    public function __construct()
    {
        $this->beforeFilter('privileged');
    }

    public function getIndex()
    {
        return Redirect::action('MySettingsController@getBackground');
    }

    public function getBackground()
    {
        $user = Sentry::getUser();

        $this->layout->content = View::make('my.settings.background')->with('user', $user);
    }

    public function postBackground()
    {
        $rules = array(
                'enable'     => 'required|in:true,false',
                'color'      => array('regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'),
                'image'      => 'url',
                'attachment' => 'in:fixed,scroll',
                'position_x' => 'in:left,center,right',
                'position_y' => 'in:top,center,bottom',
                'repeat'     => 'in:no-repeat,repeat-x,repeat-y,repeat',
                'size'       => 'in:auto,cover',
            );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $user = Sentry::getUser();

        $hasError = false;

        $hasError = ! $user->setSetting('background.enable', Input::get('enable'));
        $hasError = $hasError || (! $user->setSetting('background.color', Input::get('color')));
        $hasError = $hasError || (! $user->setSetting('background.image', Input::get('image')));

        if (Input::has('image')) {
            $hasError = $hasError || (! $user->setSetting('background.attachment', Input::get('attachment')));
            $hasError = $hasError || (! $user->setSetting('background.position.x', Input::get('position_x')));
            $hasError = $hasError || (! $user->setSetting('background.position.y', Input::get('position_y')));
            $hasError = $hasError || (! $user->setSetting('background.repeat', Input::get('repeat')));
            $hasError = $hasError || (! $user->setSetting('background.size', Input::get('size')));
        }

        $status = $hasError ? 'error' : 'success';

        Session::flash('actionFlash.status', $status);
        Session::flash('actionFlash.message', Lang::get("settings/messages.background.{$status}"));

        return Redirect::back();
    }

    public function getDropbox()
    {
        $user = Sentry::getUser();
        $this->layout->content = View::make('my.settings.dropbox')->with('user', $user);
    }

    public function postDropbox()
    {
        $validator = Validator::make(
                Input::all(),
                array(
                        'enable' => 'required|in:true,false',
                    )
            );

        if ($validator->fails()) return Redirect::back()->withInput()->withErrors($validator);

        $user = Sentry::getUser();

        $status = $user->setSetting('dropbox.enable', Input::get('enable')) ? 'success' : 'error';

        Session::flash('actionFlash.status', $status);
        Session::flash('actionFlash.message', Lang::get("settings/messages.dropbox.{$status}"));

        return Redirect::back();
    }
}
